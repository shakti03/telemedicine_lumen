import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { NGXLogger } from 'ngx-logger';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { Title } from '@angular/platform-browser';

import { AuthenticationService } from '../../../core/services/auth.service';
import { NotificationService } from '../../../core/services/notification.service';

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
    private router: Router) { }

  ngOnInit() {
    this.titleService.setTitle('Password Reset Request');

    this.registerForm = new FormGroup({
      salutation: new FormControl('Dr'),
      firstname: new FormControl('', [Validators.required]),
      lastname: new FormControl('', [Validators.required]),
      email: new FormControl('', [Validators.required, Validators.email]),
      confrim_email: new FormControl('', [Validators.required]),
      password: new FormControl('', [Validators.required]),
      agreeTerms: new FormControl(false, [Validators.required])
    });
  }

  register() {
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