import { Component, EventEmitter, Input, Output, OnInit } from '@angular/core';
import * as moment from 'moment';

@Component({
  selector: 'app-appointment-confirm',
  templateUrl: './appointment-confirm.component.html',
  styleUrls: ['./appointment-confirm.component.scss']
})
export class AppointmentConfirmComponent implements OnInit {
  @Input() appointment;
  @Output() onNew: EventEmitter<any> = new EventEmitter;

  constructor() { }

  ngOnInit(): void {
  }

  toDate(value: any) {
    return moment(value).toDate();
  }

}
