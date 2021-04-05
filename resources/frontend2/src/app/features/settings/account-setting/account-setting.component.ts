import { Component, OnInit, Output, Input, EventEmitter } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';
import { Title } from '@angular/platform-browser';

import { NotificationService } from '../../../core/services/notification.service';
import { NGXLogger } from 'ngx-logger';
import { AuthenticationService } from 'src/app/core/services/auth.service';
import { UserService } from 'src/app/core/services/user.service';
import { UiService } from 'src/app/core/services/ui.service';
import { of } from 'rxjs';

@Component({
  selector: 'app-account-setting',
  templateUrl: './account-setting.component.html',
  styleUrls: ['./account-setting.component.scss']
})
export class AccountSettingComponent implements OnInit {

  userDetailForm: FormGroup = new FormGroup({
    email: new FormControl({ value: '', disabled: true }, Validators.required),
    first_name: new FormControl('', Validators.required),
    last_name: new FormControl(''),
    phone: new FormControl(''),
    room_name: new FormControl('', Validators.required)
  });

  passwordForm: FormGroup = new FormGroup({
    password: new FormControl('', Validators.required),
    confirm_password: new FormControl('', Validators.required)
  })

  userDetail: any;

  constructor(
    private authService: AuthenticationService,
    private notificationService: NotificationService,
    private titleService: Title,
    private userService: UserService,
    private ui: UiService
  ) { }

  ngOnInit(): void {
    this.titleService.setTitle('Setting');


    this.fetchUserDetail();
  }

  fetchUserDetail() {
    this.userDetail = this.authService.getCurrentUser();
    if (this.userDetail) {
      this.userDetailForm.patchValue({
        email: this.userDetail.email,
        first_name: this.userDetail.first_name ? this.userDetail.first_name : '',
        last_name: this.userDetail.last_name ? this.userDetail.last_name : '',
        phone: this.userDetail.phone ? this.userDetail.phone : '',
        room_name: this.userDetail.room_name
      })
    }
  }

  submitProfileDetail() {
    if (this.userDetailForm.valid) {
      this.ui.showSpinner();

      this.userService.updateProfile(this.userDetailForm.value).subscribe((data: any) => {
        this.ui.stopSpinner();
        this.authService.updateUser(data.data);
        this.notificationService.openSnackBar(data.message, 2000);
      }, error => {
        this.ui.stopSpinner();
        if (error.status == 422) {
          Object.keys(error.error).forEach((key) => {
            this.userDetailForm.get(key).setErrors({ 'serverError': error.error[key] });
          });
        } else if (error.error.message) {
          this.notificationService.openSnackBar(error.error.message, 3000);
        } else {
          this.notificationService.openSnackBar(error.message, 3000);
        }
      })
    }
  }

  submitPassword() {
    if (this.passwordForm.valid) {
      const password = this.passwordForm.get('password').value;
      const passwordConfirm = this.passwordForm.get('confirm_password').value;

      if (password !== passwordConfirm) {
        this.notificationService.openSnackBar('Passwords do not match', 2000);
        return;
      }

      this.ui.showSpinner();
      this.userService.updatePassword(this.passwordForm.value).subscribe((data: any) => {
        this.ui.stopSpinner();
        this.notificationService.openSnackBar(data.message, 3000);
      }, err => {
        this.ui.stopSpinner();
        this.notificationService.openSnackBar(err.message, 3000);
      })
    }
  }
}
