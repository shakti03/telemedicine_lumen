import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FlexLayoutModule } from '@angular/flex-layout';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { CustomMaterialModule } from '../custom-material/custom-material.module';
import { LimitToPipe } from './pipes/limit-to.pipe';
import { ConfirmDialogComponent } from './confirm-dialog/confirm-dialog.component';
import { ContentPlaceholderAnimationComponent } from './content-placeholder-animation/content-placeholder-animation.component';
import { LocalDatePipe } from './pipes/local-date.pipe';
import { YesNoPipe } from './pipes/yes-no.pipe';
import { LayoutComponent } from './layout/layout.component';
import { LayoutPublicComponent } from './layout-public/layout-public.component';
import { ShowAppointmentModalComponent } from './modals/show-appointment-modal/show-appointment-modal.component';
import { EmailInviteModalComponent } from './modals/email-invite-modal/email-invite-modal.component';

@NgModule({
  imports: [
    RouterModule,
    CustomMaterialModule,
    FormsModule,
    ReactiveFormsModule,
    FlexLayoutModule,
  ],
  declarations: [
    ConfirmDialogComponent,
    ContentPlaceholderAnimationComponent,
    LimitToPipe,
    LocalDatePipe,
    YesNoPipe,
    LayoutComponent,
    LayoutPublicComponent,
    ShowAppointmentModalComponent,
    EmailInviteModalComponent
  ],
  exports: [
    FormsModule,
    ReactiveFormsModule,
    FlexLayoutModule,
    CustomMaterialModule,
    LimitToPipe,
    ConfirmDialogComponent,
    ContentPlaceholderAnimationComponent,
    LocalDatePipe,
    YesNoPipe
  ],
  entryComponents: [
    ConfirmDialogComponent
  ]
})
export class SharedModule { }
