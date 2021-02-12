import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AppointmentManagerRoutingModule } from './appointment-manager-routing.module';
import { SharedModule } from '../../shared/shared.module';
import { AppointmentManagerComponent } from './appointment-manager.component';
import { EditAppointmentComponent } from './edit-appointment/edit-appointment.component';
import { AppointmentHomeComponent } from './appointment-home/appointment-home.component';

import { FullCalendarModule } from '@fullcalendar/angular'; // the main connector. must go first
import dayGridPlugin from '@fullcalendar/daygrid'; // a plugin
import interactionPlugin from '@fullcalendar/interaction';
import { AddScheduleComponent } from './modals/add-schedule/add-schedule.component'; // a plugin

FullCalendarModule.registerPlugins([ // register FullCalendar plugins
  dayGridPlugin,
  interactionPlugin
]);

@NgModule({
  imports: [
    CommonModule,
    AppointmentManagerRoutingModule,
    SharedModule,
    FullCalendarModule
  ],
  declarations: [
    AppointmentManagerComponent,
    EditAppointmentComponent,
    AppointmentHomeComponent,
    AddScheduleComponent
  ],
  entryComponents: [
    AddScheduleComponent
  ]
})
export class AppointmentManagerModule { }
