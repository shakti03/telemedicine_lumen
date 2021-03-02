import { Component, OnInit, Output, EventEmitter, Input, ViewChild, AfterViewInit } from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { MatTableDataSource } from '@angular/material/table';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ShowAppointmentModalComponent } from '../modals/show-appointment-modal/show-appointment-modal.component'; // a plugin
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';
import * as momentTz from 'moment-timezone';

// export interface AppointmentRow {
//   patient_name: string;
//   patient_email: string;
//   symptoms: string;
//   summary: string;
//   appointment_datetime: string;
//   status: string;
//   questions: Array<any>;
// }

// const ELEMENT_DATA: AppointmentRow[] = [
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'Shakti Singh', patient_email: 'test@gmail.com', symptoms: 'fever,cough', summary: 'Lorem Ipsum', status: 'approved', questions: [{ title: "enter your age", answer: "45" }, { "title": "gender", "answer": "Male" }] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'Nitin Solanki', patient_email: 'test@gmail.com', symptoms: 'headache', summary: '', status: 'approved', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'Mahesh Solanki', patient_email: 'test@gmail.com', symptoms: 'backpain, vomiting', summary: '', status: 'approved', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'Praveen Bhati', patient_email: 'test@gmail.com', symptoms: 'fatigue', summary: '', status: 'rejected', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'Mukid Khan', patient_email: 'test@gmail.com', symptoms: 'fever', summary: '', status: 'completed', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'Ninad Gaikwad', patient_email: 'test@gmail.com', symptoms: 'cough', summary: '', status: 'approved', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'John Doe', patient_email: 'test@gmail.com', symptoms: 'fever', summary: '', status: 'approved', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'John Doe', patient_email: 'test@gmail.com', symptoms: 'fever', summary: '', status: 'approved', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'John Doe', patient_email: 'test@gmail.com', symptoms: 'fever', summary: '', status: 'approved', questions: [] },
//   { appointment_datetime: '2021-02-21 10:30:00', patient_name: 'John Doe', patient_email: 'test@gmail.com', symptoms: 'fever', summary: '', status: 'approved', questions: [] },
// ];

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

      this.dataSource.data = data.upcoming;
      this.dataSource2.data = data.past;
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
