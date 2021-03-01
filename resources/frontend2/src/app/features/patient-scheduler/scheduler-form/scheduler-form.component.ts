import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import * as moment from 'moment';

@Component({
  selector: 'app-scheduler-form',
  templateUrl: './scheduler-form.component.html',
  styleUrls: ['./scheduler-form.component.scss']
})
export class SchedulerFormComponent implements OnInit {
  @Input() meetingDetail: any;
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();

  showTimeSlots: boolean = false;
  selectedDate: string;
  selectedTime: string;
  timeslots: Array<any> = [];
  todayDate: any = moment();
  availableDates: Array<any> = [];

  myDateFilter = (m: any): boolean => {
    return this.availableDates.indexOf(m.format('YYYY-MM-DD')) != -1;
    // m.format('YYYY-MM-DD') == this.todayDate.format('YYYY-MM-DD') || m.isAfter(this.todayDate);
  }

  constructor() { }

  ngOnInit(): void {
    this.availableDates = this.meetingDetail.schedules.map((s) => {
      return this.getTimeSlots(s.date).length ? s.date : false;
    })

    this.availableDates = this.availableDates.filter((date) => {
      return date;
    })

  }

  getTimeSlots(selectedDate: string): Array<any> {

    var timeslots = [];

    this.meetingDetail.schedules.forEach((s) => {
      if (s.date == selectedDate) {
        var startTime = moment(`${selectedDate} ${s.start_time}`);
        var endTime = moment(`${selectedDate} ${s.end_time}`);

        while (startTime.isBefore(endTime)) {
          if (startTime.isAfter(this.todayDate)) {
            timeslots.push({
              label: startTime.format('hh:mm A'),
              value: startTime.format('HH:mm')
            });
          }
          startTime = startTime.add(this.meetingDetail.meeting_duration, 'minutes');

          // Break if 100
          if (timeslots.length > 100) {
            break;
          }
        }
      }
    });

    return timeslots;
  }

  selectDate(event: any) {
    this.selectedDate = event.format('YYYY-MM-DD');
    this.selectedTime = null;
    this.timeslots = this.getTimeSlots(this.selectedDate);
    this.showTimeSlots = true;
  }

  selectTime(time: string): void {
    this.selectedTime = time;
  }

  onNext() {
    if (this.selectedDate && this.selectedTime) {
      this.onSubmit.emit({
        date: this.selectedDate,
        time: this.selectedTime
      });
    }
  }

}
