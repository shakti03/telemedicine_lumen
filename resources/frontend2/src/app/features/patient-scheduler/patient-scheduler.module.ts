import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PatientSchedulerRoutingModule } from './patient-scheduler-routing.module';
import { SharedModule } from '../../shared/shared.module';

import { HomeComponent } from './home/home.component';
import { AppointmentConfirmComponent } from './appointment-confirm/appointment-confirm.component';
import { PatientDetailFormComponent } from './patient-detail-form/patient-detail-form.component';
import { SchedulerFormComponent } from './scheduler-form/scheduler-form.component';


@NgModule({
  declarations: [HomeComponent, AppointmentConfirmComponent, PatientDetailFormComponent, SchedulerFormComponent],
  imports: [
    CommonModule,
    PatientSchedulerRoutingModule,
    SharedModule
  ]
})
export class PatientSchedulerModule { }
