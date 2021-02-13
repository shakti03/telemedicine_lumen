import { Component, OnInit, Output, EventEmitter, ViewChild } from '@angular/core';
import { Schedule, AppointmentDetail, Question } from './edit-appointment-models';
import { UiService } from '../../../core/services/ui.service'


@Component({
  selector: 'app-edit-appointment',
  templateUrl: './edit-appointment.component.html',
  styleUrls: ['./edit-appointment.component.scss']
})
export class EditAppointmentComponent implements OnInit {
  @Output() edit: EventEmitter<any> = new EventEmitter();

  schedules: Array<Schedule> = [];
  appointmentDetail: AppointmentDetail = new AppointmentDetail();
  questions: Array<Question> = [];

  constructor(private ui: UiService) { }

  ngOnInit(): void {
    // this.ui.showSpinner();
    // setTimeout(() => this.ui.stopSpinner(), 2000);

  }

  saveAppointmentDetail(data: AppointmentDetail) {
    console.log(data);
  }

  saveSchedules(data: Array<Schedule>) {
    console.log(data);
  }

  saveInviteeQuestions(data: Array<Question>) {
    console.log(data);
  }
}
