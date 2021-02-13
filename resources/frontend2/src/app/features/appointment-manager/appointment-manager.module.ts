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

import { AddScheduleComponent } from './edit-appointment/modals/add-schedule/add-schedule.component';
import { QuestionsComponent } from './edit-appointment/questions/questions.component';
import { SchedulesComponent } from './edit-appointment/schedules/schedules.component';
import { AppointmentDetailComponent } from './edit-appointment/appointment-detail/appointment-detail.component';

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
    AddScheduleComponent,
    QuestionsComponent,
    SchedulesComponent,
    AppointmentDetailComponent
  ],
  entryComponents: [
    AddScheduleComponent
  ]
})
export class AppointmentManagerModule { }
