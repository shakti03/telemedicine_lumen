import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LayoutPublicComponent } from 'src/app/shared/layout-public/layout-public.component';

import { LoginComponent } from './login/login.component';
import { PasswordResetRequestComponent } from './password-reset-request/password-reset-request.component';
import { PasswordResetComponent } from './password-reset/password-reset.component';
import { RegisterComponent } from './register/register.component';

const routes: Routes = [
  {
    path: '',
    component: LayoutPublicComponent,
    children: [
      { path: 'login', component: LoginComponent },
      { path: 'password-reset-request', component: PasswordResetRequestComponent },
      { path: 'password-reset', component: PasswordResetComponent },
      { path: 'register', component: RegisterComponent }
    ]
  }
];

// const routes: Routes = [
//   {
//     path: '',
//     component: LayoutComponent,
//     children: [
//     ]
//   }
//   { path: 'login', component: LoginComponent },
//   { path: 'password-reset-request', component: PasswordResetRequestComponent },
//   { path: 'password-reset', component: PasswordResetComponent }
// ];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthRoutingModule { }
