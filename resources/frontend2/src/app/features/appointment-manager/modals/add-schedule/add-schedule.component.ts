import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import * as moment from 'moment';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';

interface DialogData {
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
    private fb: FormBuilder) { }

  ngOnInit(): void {
    let d = moment(this.data.day).startOf('day');
    let end = moment(this.data.day).endOf('day');

    while (d.isBefore(end)) {
      this.timeSlots.push({
        value: d.format('HH:mm'),
        label: d.format('hh:mm A')
      })

      d.add(15, 'minutes');
    }

    this.addScheduleForm = new FormGroup({
      start: new FormControl('', Validators.required),
      end: new FormControl('', Validators.required)
    });
  }

  addSchedule(frm: FormGroup, nativeForm: FormGroupDirective) {
    console.log(frm.value)
    if (frm.valid) {
      this.schedules.push(new FormGroup({
        start: new FormControl(frm.value.start, Validators.required),
        end: new FormControl(frm.value.end, Validators.required)
      }));

      frm.reset();
      console.log(nativeForm);
      nativeForm.resetForm()
    }
    ;
  }

  deleteSchedule(index: number) {
    this.schedules.removeAt(index);
  }

  save() {
    this.data.slots = this.schedules.value;
    this.dialogRef.close(this.data);
  }

}