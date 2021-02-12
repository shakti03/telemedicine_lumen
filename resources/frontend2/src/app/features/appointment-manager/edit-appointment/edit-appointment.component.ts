import { Component, OnInit, Output, EventEmitter, ViewChild } from '@angular/core';
import { CalendarOptions, FullCalendarComponent, DateSelectArg, EventClickArg } from '@fullcalendar/angular'; // useful for typechecking
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import * as moment from 'moment';
import { AddScheduleComponent } from '../modals/add-schedule/add-schedule.component'; // a plugin


@Component({
  selector: 'app-edit-appointment',
  templateUrl: './edit-appointment.component.html',
  styleUrls: ['./edit-appointment.component.scss']
})
export class EditAppointmentComponent implements OnInit {
  @Output() edit: EventEmitter<any> = new EventEmitter();

  @ViewChild('calendar') calendarComponent: FullCalendarComponent;

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

  showCalendar: boolean = false;

  constructor(public dialog: MatDialog) { }

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
      console.log(result);

      if (result && result.slots?.length) {
        let start = moment(selectInfo.startStr);
        let end = moment(selectInfo.endStr);

        while (start.isBefore(end)) {
          result.slots.forEach(slot => {
            calendarApi.addEvent({
              id: 'IDI' + Date.now(),
              title: `${slot.start} - ${slot.end}`,
              start: start.format('YYYY-MM-DD') + ' ' + slot.start,
              end: start.format('YYYY-MM-DD') + ' ' + slot.end,
              allDay: false
            });

            console.log(`${slot.start} - ${slot.end}`);
          });

          start.add(1, 'day');
        }
      }

    });
  }

  prevStep() {

  }

  nextStep() {

  }

  showCalendarView() {
    this.showCalendar = true;
  }
}
