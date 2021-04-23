import { Component, OnInit } from '@angular/core';
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';
import * as momentTz from 'moment-timezone';
import * as moment from 'moment';

@Component({
  selector: 'app-patient-queue',
  templateUrl: './patient-queue.component.html',
  styleUrls: ['./patient-queue.component.scss']
})
export class PatientQueueComponent implements OnInit {
  patients: Array<any> = [];
  
  constructor(
    private appointmentService: AppointmentService,
    private notificationService: NotificationService,
    private ui: UiService
  ) { }

  ngOnInit(): void {
    this.fetchAppointments();
  }

  fetchAppointments(): void {
    let zone_name = momentTz.tz.guess();

    this.appointmentService.getWaitingAppointments({ timezone: zone_name}).subscribe((data: any) => {

      this.patients = data ? data.map((row: any) => {
        row.appointment_datetime = moment(row.appointment_datetime).toDate();
        return row;
      }) : [];
    }, err => {

      this.notificationService.openSnackBar(err.error.message);
    })
  }

}
