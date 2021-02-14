import { Component, OnInit, ViewChild } from '@angular/core';
import { MatSort } from '@angular/material/sort';
import { MatTableDataSource } from '@angular/material/table';
import { NGXLogger } from 'ngx-logger';
import { Title } from '@angular/platform-browser';

import { NotificationService } from '../../core/services/notification.service';
import { AppointmentService } from 'src/app/core/services/appointment.service';

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
    private appointmentService: AppointmentService
  ) { }

  ngOnInit() {
    this.titleService.setTitle('Appointment Manager');
    // this.logger.log('Appointments loaded');

    this.fetchData();
  }

  fetchData() {
    console.log('fetch data');
    this.appointmentService.getAppointmentDetail().subscribe((data: any) => {
      this.appointmentDetail = data;
    });
  }

  switchTab(tab: Tabs) {
    this.activeScreen = tab;
  }
}
