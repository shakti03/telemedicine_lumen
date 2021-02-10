import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CustomersRoutingModule } from './appointment-manager-routing.module';
import { SharedModule } from '../../shared/shared.module';
import { CustomerListComponent } from './base/base.component';
import { CreateAppointmentComponent } from './create-appointment/create-appointment.component';
import { AppointmentListComponent } from './appointment-list/appointment-list.component';

@NgModule({
  imports: [
    CommonModule,
    CustomersRoutingModule,
    SharedModule
  ],
  declarations: [
    CustomerListComponent,
    CreateAppointmentComponent,
    AppointmentListComponent
  ],
  entryComponents: [
  ]
})
export class CustomersModule { }
