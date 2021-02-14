import { Component, OnInit, ViewChild, Output, Input, EventEmitter } from '@angular/core';
import { CalendarOptions, FullCalendarComponent, DateSelectArg, EventClickArg } from '@fullcalendar/angular'; // useful for typechecking
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import * as moment from 'moment';
import * as momentTz from 'moment-timezone';
import { AddScheduleComponent } from '../modals/add-schedule/add-schedule.component'; // a plugin


@Component({
  selector: 'edit-schedules',
  templateUrl: './schedules.component.html',
  styleUrls: ['./schedules.component.scss']
})
export class SchedulesComponent implements OnInit {
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();
  @Input() schedules;
  @ViewChild('calendar') calendarComponent: FullCalendarComponent;

  timezoneName: string;

  calendarOptions: CalendarOptions = {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    displayEventTime: false,
    fixedWeekCount: false,
    selectable: true,
    select: this.handleDateClick.bind(this),
    selectAllow: (info) => {
      if (moment(info.start).isBefore(moment().subtract(1, 'day')))
        return false;
      return true;
    }
  };

  constructor(public dialog: MatDialog) {
    let zone_name = momentTz.tz.guess();
    var timezone = momentTz.tz(zone_name)
    console.log(timezone.format('Z'));

    this.timezoneName = `${zone_name} (GMT ${timezone.format('Z')})`;
  }

  ngOnInit(): void {
  }

  handleDateClick(selectInfo: DateSelectArg) {
    console.log(selectInfo)

    const calendarApi = selectInfo.view.calendar;

    calendarApi.unselect(); // clear date selection

    const dialogRef = this.dialog.open(AddScheduleComponent, {
      data: {
        day: moment(selectInfo.startStr).startOf('day').toDate(),
        slots: []
      }
    });

    dialogRef.afterClosed().subscribe(result => {

      if (result && result.slots?.length) {
        let start = moment(selectInfo.startStr);
        let end = moment(selectInfo.endStr);
        let scheduleArr = [];

        while (start.isBefore(end)) {
          result.slots.forEach(slot => {
            let eventDetail = {
              id: 'IDI' + Date.now(),
              title: `${slot.start} - ${slot.end}`,
              start: start.format('YYYY-MM-DD') + ' ' + slot.start,
              end: start.format('YYYY-MM-DD') + ' ' + slot.end,
              allDay: false
            }
            scheduleArr.push(eventDetail);
            calendarApi.addEvent(eventDetail);
          });

          start.add(1, 'day');
        }

        this.onSubmit.emit(scheduleArr);
      }

    });
  }

}
