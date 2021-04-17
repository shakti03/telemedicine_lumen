import { Component, OnInit, ViewChild, Output, Input, EventEmitter } from '@angular/core';
import { CalendarOptions, FullCalendarComponent, DateSelectArg, EventClickArg } from '@fullcalendar/angular'; // useful for typechecking
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import * as moment from 'moment';
import * as momentTz from 'moment-timezone';
import { AddScheduleComponent } from '../modals/add-schedule/add-schedule.component'; // a plugin
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';

@Component({
  selector: 'edit-schedules',
  templateUrl: './schedules.component.html',
  styleUrls: ['./schedules.component.scss']
})
export class SchedulesComponent implements OnInit {
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();
  @Input() schedules;
  @Input() meeting;
  @ViewChild('calendar') calendarComponent: FullCalendarComponent;

  timezoneName: string;
  meetingDuration: number = 30;

  meetingDurationsList: Array<{ value: number, label: string }> = [
    { value: 15, label: '15 Minutes' },
    { value: 30, label: '30 Minutes' },
    { value: 45, label: '45 Minutes' }
  ];

  calendarOptions: CalendarOptions = {
    events: this.loadEvents.bind(this),
    height: (window as any).device == "mobile" ? 'auto' : null,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    displayEventTime: false,
    fixedWeekCount: false,
    selectable: true,
    selectLongPressDelay: this.handleDateClick.bind(this),
    select: this.handleDateClick.bind(this),
    selectAllow: (info) => {
      if (moment(info.start).isBefore(moment().subtract(1, 'day')))
        return false;
      return true;
    }
  };

  constructor(
    public dialog: MatDialog,
    private appointmentService: AppointmentService,
    private notificationService: NotificationService,
    private ui: UiService
  ) {
    let zone_name = momentTz.tz.guess();
    var timezone = momentTz.tz(zone_name);

    this.timezoneName = `${zone_name} (GMT ${timezone.format('Z')})`;
  }

  ngOnInit(): void {
    if (this.meeting) {
      this.meetingDuration = this.meeting.meeting_duration
    }
  }

  loadEvents(info, successCallback) {
    if (this.schedules && this.schedules.length) {

      successCallback(this.schedules.map((schedule) => {
        return {
          id: schedule.id,
          title: schedule.title,
          date: schedule.date,
          start_time: schedule.start_time,
          end_time: schedule.end_time,
          start: `${schedule.date} ${schedule.start_time}`,
          end: `${schedule.date} ${schedule.end_time}`
        }
      }));
    }
  }

  handleDateClick(selectInfo: DateSelectArg) {

    let start = moment(selectInfo.startStr);
    let end = moment(selectInfo.endStr);

    const calendarApi = selectInfo.view.calendar;

    calendarApi.unselect(); // clear date selection

    let events = calendarApi.getEvents();

    let selectedDate = start.format('YYYY-MM-DD');
    let slots = [];
    events.forEach((event: any) => {
      let startDateTime = moment(event.startStr);
      let endDateTime = moment(event.endStr);
      if (startDateTime.format('YYYY-MM-DD') == selectedDate) {
        slots.push({
          start: startDateTime.format('HH:mm'),
          end: endDateTime.format('HH:mm')
        })
      }
    })

    const dialogRef = this.dialog.open(AddScheduleComponent, {
      data: {
        selectInfo: selectInfo,
        day: moment(selectInfo.startStr).startOf('day').toDate(),
        slots: slots
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      this.onSubmit.emit(result);

      setTimeout(() => {
        this.calendarComponent.getApi().refetchEvents();
      }, 500);
    });
  }

  udpateMeetingDuration() {

    this.meeting.action = 'update_duration';
    this.meeting.meeting_duration = this.meetingDuration;
    this.ui.showSpinner();
    this.appointmentService.updateAppointmentDetail(this.meeting).subscribe((data: any) => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(data.message, 3000)
    }, err => {
      this.ui.stopSpinner();
      this.notificationService.openSnackBar(err.message)
    })
  }

}
