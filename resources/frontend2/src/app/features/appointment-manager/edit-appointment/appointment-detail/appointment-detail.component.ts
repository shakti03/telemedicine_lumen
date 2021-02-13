import { Component, OnInit, Output, Input, EventEmitter } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';

@Component({
  selector: 'edit-appointment-detail',
  templateUrl: './appointment-detail.component.html',
  styleUrls: ['./appointment-detail.component.scss']
})
export class AppointmentDetailComponent implements OnInit {
  @Input() apppointmentDetail;
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();

  detailForm: FormGroup;

  constructor() { }

  ngOnInit(): void {
    this.detailForm = new FormGroup({
      name: new FormControl('', Validators.required),
      location: new FormControl('phone', Validators.required),
      description: new FormControl('', Validators.required)
    })
  }

  submit() {
    if (this.detailForm.valid) {
      this.onSubmit.emit(this.detailForm.value)
    }
  }
}
