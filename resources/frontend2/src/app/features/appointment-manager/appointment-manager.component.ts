import { Component, OnInit, ViewChild } from '@angular/core';
import { MatSort } from '@angular/material/sort';
import { MatTableDataSource } from '@angular/material/table';
import { NGXLogger } from 'ngx-logger';
import { Title } from '@angular/platform-browser';

import { NotificationService } from '../../core/services/notification.service';
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { UiService } from 'src/app/core/services/ui.service';

enum Tabs {
  appointment_home,
  edit
}

@Component({
  selector: 'app-appointment-manager',
  templateUrl: './appointment-manager.component.html',
  styleUrls: ['./appointment-manager.component.css']
})
export class AppointmentManagerComponent implements OnInit {
  @ViewChild(MatSort, { static: true }) sort: MatSort;

  public Tabs = Tabs;
  activeScreen: Tabs = Tabs.appointment_home;

  appointmentDetail: any = {};

  constructor(
    private logger: NGXLogger,
    private notificationService: NotificationService,
    private titleService: Title,
    private appointmentService: AppointmentService,
    private ui: UiService
  ) { }

  ngOnInit() {
    this.titleService.setTitle('Appointment Manager');
    // this.logger.log('Appointments loaded');

    this.fetchData();
  }

  fetchData() {
    // this.ui.showSpinner();

    this.appointmentService.getAppointmentDetail().subscribe((data: any) => {
      // this.ui.stopSpinner();
      this.appointmentDetail = data;
      console.log('first', this.appointmentDetail);
    }, err => {
      // this.ui.stopSpinner();
      this.notificationService.openSnackBar(err.message);
    });
  }

  onEdit(data: any) {
    if (data) {
      switch (data.type) {
        case 'schedules':
          this.appointmentDetail.schedules = data.data; break;
        case 'questions':
          this.appointmentDetail.questions = data.data; break;
        default:
          this.appointmentDetail.title = data.title;
          this.appointmentDetail.location = data.location;
          this.appointmentDetail.description = data.description;
          break;
      }
    }

  }

  switchTab(tab: Tabs) {
    this.activeScreen = tab;
  }
}
