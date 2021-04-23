import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import * as moment from 'moment';

import { UiService } from 'src/app/core/services/ui.service'
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';


interface DialogData {
  appointment: any
}

@Component({
  selector: 'shared-show-appointment-modal',
  templateUrl: './show-appointment-modal.component.html',
  styleUrls: ['./show-appointment-modal.component.scss']
})
export class ShowAppointmentModalComponent implements OnInit {
  appointment: any;
  now: any = moment().format('YYYY-MM-DD HH:mm:ss');

  constructor(
    public dialogRef: MatDialogRef<ShowAppointmentModalComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData,
    private ui: UiService,
    private appointmentService: AppointmentService,
    private notificationService: NotificationService) { }

  ngOnInit(): void {
    this.appointment = this.data.appointment;
  }

  approve() {
    this.ui.showSpinner();
    this.appointmentService.changeAppointmentStatus(this.appointment.uuid, { status: 1 }).subscribe((data: any) => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(data.message, 2000);
      this.dialogRef.close({ 'status': 'approved' });
    }, error => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(error.error.message)
    })
  }

  reject() {
    this.ui.showSpinner();
    this.appointmentService.changeAppointmentStatus(this.appointment.uuid, { status: 2 }).subscribe((data: any) => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(data.message, 2000);
      this.dialogRef.close({ 'status': 'rejected' });
    }, error => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(error.error.message)
    })
  }

}
