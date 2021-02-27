import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import * as moment from 'moment';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';

import { UiService } from 'src/app/core/services/ui.service'
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';


interface DialogData {
  selectInfo: any,
  day: Date,
  slots: Array<{ start: number, end: number }>
}

interface scheduleData {
  start: string,
  end: string
}

class ScheduleForm {
  form: FormGroup = new FormGroup({
    start: new FormControl('', Validators.required),
    end: new FormControl('', Validators.required)
  })

  constructor(initialValues?: scheduleData) {
    if (initialValues) {
      this.form.setValue(initialValues)
    }
  }
}


@Component({
  selector: 'add-schedule-dialog',
  templateUrl: './add-schedule.component.html',
  styleUrls: ['./add-schedule.component.scss']
})
export class AddScheduleComponent implements OnInit {
  timeSlots: Array<{ value: string, label: string }> = [];
  schedules: FormArray = new FormArray([]);
  addScheduleForm: FormGroup;

  constructor(
    public dialogRef: MatDialogRef<AddScheduleComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData,
    private fb: FormBuilder,
    private ui: UiService,
    private appointmentService: AppointmentService,
    private notificationService: NotificationService) { }

  ngOnInit(): void {
    let d = moment(this.data.day).startOf('day');
    let end = moment(this.data.day).endOf('day');

    while (d.isBefore(end)) {
      this.timeSlots.push({
        value: d.format('HH:mm'),
        label: d.format('hh:mm A')
      })

      d.add(30, 'minutes');
    }

    this.addScheduleForm = new FormGroup({
      start: new FormControl('', Validators.required),
      end: new FormControl('', Validators.required)
    });

    if (this.data.slots) {
      this.data.slots.forEach((slot: any) => {
        this.schedules.push(new FormGroup({
          start: new FormControl(slot.start, Validators.required),
          end: new FormControl(slot.end, Validators.required)
        }))
      })
    }
  }

  addSchedule(frm: FormGroup, nativeForm: FormGroupDirective) {

    if (frm.valid) {
      this.schedules.push(new FormGroup({
        start: new FormControl(frm.value.start, Validators.required),
        end: new FormControl(frm.value.end, Validators.required)
      }));

      frm.reset();
      nativeForm.resetForm();
    }
  }

  deleteSchedule(index: number) {
    this.schedules.removeAt(index);
  }

  save() {
    this.data.slots = this.schedules.value;
    // this.dialogRef.close(this.data);
    const { slots, selectInfo } = this.data;

    if (slots?.length) {
      let start = moment(selectInfo.startStr);
      let end = moment(selectInfo.endStr);
      let scheduleArr = [];

      while (start.isBefore(end)) {
        slots.forEach(slot => {
          let eventDetail = {
            title: `${slot.start} - ${slot.end}`,
            date: start.format('YYYY-MM-DD'),
            start_time: slot.start,
            end_time: slot.end,
          }
          scheduleArr.push(eventDetail);
        });

        start.add(1, 'day');
      }

      this.ui.showSpinner();

      this.appointmentService.updateSchedules({ schedules: scheduleArr }).subscribe((result: any) => {
        this.ui.stopSpinner();
        this.notificationService.openSnackBar(result.message, 1000);
        this.dialogRef.close(result.data);
      }, error => {
        this.ui.stopSpinner();
        this.notificationService.openSnackBar(error.message);
      })
    }
  }

}