import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { NGXLogger } from 'ngx-logger';
import { FormGroup, FormControl, Validators, AbstractControl, ValidationErrors } from '@angular/forms';
import { Title } from '@angular/platform-browser';

import { AuthenticationService } from '../../../core/services/auth.service';
import { NotificationService } from '../../../core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {

  private email: string;
  registerForm: FormGroup;
  form: FormGroup;
  loading: boolean;
  terms: boolean = false;

  constructor(private authService: AuthenticationService,
    private notificationService: NotificationService,
    private titleService: Title,
    private router: Router,
    private ui: UiService) { }

  ngOnInit() {
    this.titleService.setTitle('Register');

    this.registerForm = new FormGroup({
      salutation: new FormControl('Dr'),
      firstname: new FormControl('', [Validators.required]),
      lastname: new FormControl('', [Validators.required]),
      email: new FormControl('', [Validators.required, Validators.email]),
      confirm_email: new FormControl('', [
        Validators.required,
        RegisterComponent.matchValues('email')
      ]),
      password: new FormControl('', Validators.required),
      confirm_password: new FormControl('', [
        Validators.required,
        RegisterComponent.matchValues('password')
      ]),
      agreeTerms: new FormControl(false, [Validators.required])
    });
  }

  public static matchValues(
    matchTo: string // name of the control to match to
  ): (AbstractControl) => ValidationErrors | null {
    return (control: AbstractControl): ValidationErrors | null => {
      return !!control.parent &&
        !!control.parent.value &&
        control.value === control.parent.controls[matchTo].value
        ? null
        : { notSame: true };
    };
  }

  register() {
    if (this.registerForm.valid) {
      this.ui.showSpinner();
      this.authService.register(this.registerForm.value).subscribe((result: any) => {
        this.ui.stopSpinner();
        this.notificationService.openSnackBar(result.message);
      }, (error) => {
        console.log(error);
        this.ui.stopSpinner();
        if (error && error.error) {
          if (error.status == 422) {
            Object.keys(error.error).forEach((key) => {
              this.registerForm.get(key).setErrors({ 'serverError': error.error[key] });
            });
          } else if (error.error.message) {
            this.notificationService.openSnackBar(error.error.message, 3000);
          } else {
            this.notificationService.openSnackBar(error.message, 3000);
          }

        }
      })
    }
    // console.log(this.registerForm.value, this.registerForm.valid);
    // this.loading = true;
    // this.authService.passwordResetRequest(this.email)
    //   .subscribe(
    //     results => {
    //       this.router.navigate(['/auth/login']);
    //       this.notificationService.openSnackBar('Password verification mail has been sent to your email address.');
    //     },
    //     error => {
    //       this.loading = false;
    //       this.notificationService.openSnackBar(error.error);
    //     }
    //   );
  }

  login() {
    this.router.navigate(['/auth/login']);
  }
}