import { Component, OnInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ShowAppointmentModalComponent } from 'src/app/shared/modals/show-appointment-modal/show-appointment-modal.component'; // a plugin
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';
import * as momentTz from 'moment-timezone';
import * as moment from 'moment';

@Component({
  selector: 'app-appointments',
  templateUrl: './appointments.component.html',
  styleUrls: ['./appointments.component.scss']
})
export class AppointmentsComponent implements OnInit {
  dataSource = new MatTableDataSource<any>([]);
  displayedColumns: string[] = ['datetime', 'name', 'status', 'action'];
  
  constructor(
    public dialog: MatDialog,
    private appointmentService: AppointmentService,
    private notificationService: NotificationService,
    private ui: UiService
  ) { }

  ngOnInit(): void {
    this.fetchAppointments();
  }

  fetchAppointments(): void {
    let zone_name = momentTz.tz.guess();
    // var timezone = momentTz.tz(zone_name);

    this.appointmentService.getAppointments({ timezone: zone_name, limit:5, type:'upcoming' }).subscribe((data: any) => {

      this.dataSource.data = data.upcoming ? data.upcoming.map((row: any) => {
        row.appointment_datetime = moment(row.appointment_datetime).toDate();
        return row;
      }) : [];
    }, err => {

      this.notificationService.openSnackBar(err.error.message);
    })
  }

  showDetail(element: any) {
    const dialogRef = this.dialog.open(ShowAppointmentModalComponent, {
      data: {
        appointment: element
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.fetchAppointments();
      }

    });
  }

}
