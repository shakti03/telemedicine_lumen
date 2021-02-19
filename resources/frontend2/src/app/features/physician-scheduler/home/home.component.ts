import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { COMMA, ENTER } from '@angular/cdk/keycodes';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';
import { MatChipInputEvent } from '@angular/material/chips';
import { of, Observable } from 'rxjs';
import { map, startWith, debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';


import { MatAutocompleteSelectedEvent, MatAutocomplete } from '@angular/material/autocomplete';
import { CommonService } from 'src/app/core/services/common.service';

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
    name: new FormControl('', Validators.required),
    email: new FormControl('', Validators.required),
    location: new FormControl('', Validators.required),
    description: new FormControl('', Validators.required),
    symptoms: new FormControl('', Validators.required),
    question1: new FormControl('', Validators.required),
    question2: new FormControl('', Validators.required),
    question3: new FormControl('', Validators.required),
    question4: new FormControl('', Validators.required)
  })
  activeStep: Step = Step.step1

  // visible = true;
  // selectable = true;
  // removable = true;
  separatorKeysCodes: number[] = [ENTER, COMMA];
  symptomCtrl = new FormControl();
  filteredsymptoms: Observable<any[]>;
  symptoms: string[] = [];
  allsymptoms: string[] = ['Fever', 'Cough', 'Headache', 'Fatigue', 'Bodyache'];

  @ViewChild('symptomInput') symptomInput: ElementRef<HTMLInputElement>;
  @ViewChild('auto') matAutocomplete: MatAutocomplete;

  constructor(private commonService: CommonService) { }

  ngOnInit(): void {
    document.getElementsByTagName('body')[0].classList.add('bg-default');
    console.log('here loaded');

    this.filteredsymptoms = this.symptomCtrl.valueChanges.pipe(
      startWith(''),
      debounceTime(400),
      distinctUntilChanged(),
      switchMap(val => {
        return this._filter(val || '')
      })
    )
  }

  add(event: MatChipInputEvent): void {
    const input = event.input;
    const value = event.value;

    // Add our symptom
    if ((value || '').trim()) {
      this.symptoms.push(value.trim());
    }

    // Reset the input value
    if (input) {
      input.value = '';
    }

    this.symptomCtrl.setValue(null);
  }

  remove(symptom: string): void {
    const index = this.symptoms.indexOf(symptom);

    if (index >= 0) {
      this.symptoms.splice(index, 1);
    }
  }

  selected(event: MatAutocompleteSelectedEvent): void {
    this.symptoms.push(event.option.viewValue);
    this.symptomInput.nativeElement.value = '';
    this.symptomCtrl.setValue(null);
  }

  private _filter(value: string): Observable<any[]> {
    return this.commonService.getSypmtoms()
      .pipe(
        map((response: Array<any>) => response.filter(option => {
          return option.name.toLowerCase().indexOf(value.toLowerCase()) === 0
        }))
      )
  }

  onSelect(event: any) {
    this.showTimeSlots = true;
  }

  gotoStep(step: Step) {
    this.activeStep = step;
  }

}
