import { Component, OnInit, ViewChild } from '@angular/core';
import { MatSort } from '@angular/material/sort';
import { MatTableDataSource } from '@angular/material/table';
import { NGXLogger } from 'ngx-logger';
import { Title } from '@angular/platform-browser';

import { NotificationService } from '../../core/services/notification.service';

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
  public Tabs = Tabs;
  activeScreen: Tabs = Tabs.appointment_home;

  @ViewChild(MatSort, { static: true }) sort: MatSort;

  constructor(
    private logger: NGXLogger,
    private notificationService: NotificationService,
    private titleService: Title
  ) { }

  ngOnInit() {
    this.titleService.setTitle('Appointments');
    this.logger.log('Appointments loaded');

  }

  switchTab(tab: Tabs) {
    this.activeScreen = tab;
  }
}
