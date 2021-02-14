import { Component, OnInit, Output, EventEmitter, ViewChild, Input } from '@angular/core';
import { Schedule, AppointmentDetail, Question } from './edit-appointment-models';
import { UiService } from '../../../core/services/ui.service'
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';


@Component({
  selector: 'app-edit-appointment',
  templateUrl: './edit-appointment.component.html',
  styleUrls: ['./edit-appointment.component.scss']
})
export class EditAppointmentComponent implements OnInit {
  @Output() onEdit: EventEmitter<any> = new EventEmitter();
  @Input() meeting: any;

  schedules: Array<Schedule> = [];
  appointmentDetail: AppointmentDetail = new AppointmentDetail();
  questions: Array<Question> = [];

  constructor(
    private ui: UiService,
    private appointmentService: AppointmentService,
    private notificationService: NotificationService) { }

  ngOnInit(): void {

  }

  saveAppointmentDetail(fd: AppointmentDetail) {
    this.ui.showSpinner();

    this.appointmentService.updateAppointmentDetail(fd).subscribe((result: any) => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(result.message, 1000);
      this.onEdit.emit(fd);
    }, error => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(error.message);
    })
  }

  saveSchedules(data: Array<any>) {
    this.meeting.schedules = data;
  }

  saveInviteeQuestions(fd: Array<Question>) {
    this.appointmentService.updateQuestions({ questions: fd }).subscribe((result: any) => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(result.message, 1000);
      this.onEdit.emit({
        type: 'questions',
        data: result
      });
    }, error => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(error.message);
    })
  }
}
