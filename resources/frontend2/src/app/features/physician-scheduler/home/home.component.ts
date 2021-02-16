import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';

enum Step {
  step1,
  step2
};

@Component({
  selector: 'physician-scheduler-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  Step = Step;
  showTimeSlots: boolean = false;
  detailForm: FormGroup = new FormGroup({
    title: new FormControl('', Validators.required),
    location: new FormControl('', Validators.required),
    description: new FormControl('', Validators.required)
  })
  activeStep: Step = Step.step1
  constructor() { }

  ngOnInit(): void {
    document.getElementsByTagName('body')[0].classList.add('bg-default');
    console.log('here loaded');
  }

  onSelect(event: any) {
    this.showTimeSlots = true;
  }

  gotoStep(step: Step) {
    this.activeStep = step;
  }

}
