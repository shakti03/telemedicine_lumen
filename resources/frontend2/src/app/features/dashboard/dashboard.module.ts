import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ChartsModule } from 'ng2-charts';

import { DashboardRoutingModule } from './dashboard-routing.module';
import { SharedModule } from '../../shared/shared.module';
import { DashboardHomeComponent } from './dashboard-home/dashboard-home.component';
import { PatientQueueComponent } from './patient-queue/patient-queue.component';
import { AppointmentsComponent } from './appointments/appointments.component';
import { TotalEarningComponent } from './total-earning/total-earning.component';
import { AnalyticsComponent } from './analytics/analytics.component';

@NgModule({
  declarations: [DashboardHomeComponent, PatientQueueComponent, AppointmentsComponent, TotalEarningComponent, AnalyticsComponent],
  imports: [
    CommonModule,
    DashboardRoutingModule,
    SharedModule,
    ChartsModule
  ],
  entryComponents: []
})
export class DashboardModule { }
