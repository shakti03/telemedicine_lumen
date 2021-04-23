import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router'

import { CommonService } from 'src/app/core/services/common.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';
import { PatientDetailFormComponent } from '../patient-detail-form/patient-detail-form.component';

enum Step {
  selectDateTime,
  enterPatientDetail
};

@Component({
  selector: 'physician-scheduler-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  @ViewChild('patientDetailForm') patientDetailForm: PatientDetailFormComponent;

  Step = Step;
  activeStep: Step = Step.selectDateTime

  physicianLink: string = '';
  meetingDetail: any;
  selectedDate: string;
  selectedTime: string;
  appointment: any = null;
  loading: boolean = false;

  constructor(
    private ui: UiService,
    private router: Router,
    private route: ActivatedRoute,
    private commonService: CommonService,
    private notificationService: NotificationService) {

  }

  ngOnInit(): void {
    document.getElementsByTagName('body')[0].classList.add('bg-default');
    this.physicianLink = this.route.snapshot.params['user_url'];
    this.fetchMeetingDetail();

    // this.appointment = { "title": "XYZ Clinic", "physician_name": "Admin", "appointment_date": "2021-02-27", "appointment_time": "20:30", "appointment_duration": 30 }
  }

  fetchMeetingDetail() {
    if (this.physicianLink) {
      this.loading = true;
      this.commonService.getPhysicianMeetingDetail(this.physicianLink).subscribe((data: any) => {
        this.loading = false;
        this.meetingDetail = data;
      }, err => {
        this.loading = false;
      });
    }
  }

  newAppointment() {
    this.selectedDate = null;
    this.selectedTime = null;
    this.appointment = null;
    this.activeStep = Step.selectDateTime;
  }

  gotoStep(step: Step) {
    this.activeStep = step;
  }

  selectDateAndTime(value: any) {
    this.selectedDate = value.date;
    this.selectedTime = value.time;

    this.gotoStep(Step.enterPatientDetail);
  }

  saveAppointment(values: any) {

    let fd = {
      meeting_id: this.meetingDetail.uuid,
      appointment_date: this.selectedDate,
      appointment_time: this.selectedTime,
      ...values
    };

    this.ui.showSpinner();
    this.commonService.createAppointment(fd).subscribe((r: any) => {
      this.ui.stopSpinner();
      this.appointment = r.data;

      this.patientDetailForm.detailForm.reset();
    }, error => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(error.error.message);
    });
  }
}
