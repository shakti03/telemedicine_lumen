import { Component, OnInit, Output, EventEmitter, Input, ViewChild, AfterViewInit } from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { MatTableDataSource } from '@angular/material/table';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ShowAppointmentModalComponent } from '../modals/show-appointment-modal/show-appointment-modal.component'; // a plugin
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';
import * as momentTz from 'moment-timezone';
import * as moment from 'moment';

@Component({
  selector: 'app-appointment-home',
  templateUrl: './appointment-home.component.html',
  styleUrls: ['./appointment-home.component.scss']
})
export class AppointmentHomeComponent implements OnInit, AfterViewInit {
  @Output() edit: EventEmitter<any> = new EventEmitter();
  @Input() meeting: any;
  displayedColumns: string[] = ['datetime', 'name', 'status', 'action'];
  dataSource = new MatTableDataSource<any>([]);
  dataSource2 = new MatTableDataSource<any>([]);

  @ViewChild(MatPaginator, { static: false })
  set upcomingAppointmentPaginator(value: MatPaginator) {
    this.dataSource.paginator = value;
  }

  @ViewChild('pastAppointmentPaginator', { static: false })
  set pastAppointmentPaginator(value: MatPaginator) {
    this.dataSource2.paginator = value;
  }

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

    this.appointmentService.getAppointments({ timezone: zone_name }).subscribe((data: any) => {

      this.dataSource.data = data.upcoming ? data.upcoming.map((row: any) => {
        row.appointment_datetime = moment(row.appointment_datetime).toDate();
        return row;
      }) : [];

      this.dataSource2.data = data.past ? data.past.map((row: any) => {
        row.appointment_datetime = moment(row.appointment_datetime).toDate();
        return row;
      }) : [];
    }, err => {

      this.notificationService.openSnackBar(err.message);
    })
  }

  ngAfterViewInit() {
    // this.dataSource.paginator = this.upcomingAppointmentPaginator;
    // this.dataSource2.paginator = this.pastAppointmentPaginator;
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
