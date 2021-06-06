import { Component, Input, OnInit, Output, EventEmitter, ViewChild, ElementRef } from '@angular/core';
import { MatAutocompleteSelectedEvent, MatAutocomplete } from '@angular/material/autocomplete';
import { COMMA, ENTER, TAB } from '@angular/cdk/keycodes';
import { FormGroup, Validators, FormArray, FormControl, ValidationErrors } from '@angular/forms';
import { MatChipInputEvent } from '@angular/material/chips';
import { of, Observable } from 'rxjs';
import { map, startWith, debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';

import { CommonService } from 'src/app/core/services/common.service';

@Component({
  selector: 'app-patient-detail-form',
  templateUrl: './patient-detail-form.component.html',
  styleUrls: ['./patient-detail-form.component.scss']
})
export class PatientDetailFormComponent implements OnInit {
  @Input() meetingDetail: any;
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();
  @Output() back: EventEmitter<any> = new EventEmitter();


  allsymptoms: string[] = ['Fever', 'Cough', 'Headache', 'Fatigue', 'Bodyache'];
  separatorKeysCodes: number[] = [ENTER, COMMA, TAB];
  symptomCtrl = new FormControl();
  filteredsymptoms: Observable<any[]>;
  detailForm: FormGroup = new FormGroup({
    name: new FormControl('', Validators.required),
    email: new FormControl('', [Validators.required, Validators.email]),
    // location: new FormControl('', Validators.required),
    description: new FormControl('', Validators.required),
    symptoms: new FormControl([]),
    questions: new FormArray([])
  });

  @ViewChild('symptomInput') symptomInput: ElementRef<HTMLInputElement>;
  @ViewChild('symptomsAutoComplete') matAutocomplete: MatAutocomplete;


  constructor(private commonService: CommonService) { }

  ngOnInit(): void {
    if (this.meetingDetail) {
      this.meetingDetail.questions.forEach((q) => {
        this.questions.push(new FormControl('', Validators.required))
      })
    }

    this.filteredsymptoms = this.symptomCtrl.valueChanges.pipe(
      startWith(''),
      debounceTime(400),
      distinctUntilChanged(),
      switchMap(val => {
        return this._filter(val || '')
      })
    )
  }



  get symptoms() {
    return this.detailForm.get('symptoms').value;
  }

  get questions() {
    return this.detailForm.get('questions') as FormArray;
  }


  add(event: MatChipInputEvent): void {
    const input = event.input;
    const value = event.value;

    // Add our symptom
    if ((value || '').trim()) {
      this.symptoms.push(value.trim());
      this.symptomCtrl.setValue(this.symptoms);
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
      this.symptomCtrl.setValue(this.symptoms);
    }
  }

  selected(event: MatAutocompleteSelectedEvent): void {
    this.symptoms.push(event.option.viewValue);
    this.symptomInput.nativeElement.value = '';
    this.symptomCtrl.setValue(null);
  }

  private _filter(value: string): Observable<any[]> {
    return this.commonService.getSypmtoms(value)
      .pipe(
        map((response: Array<any>) => response.filter(option => {
          return option.name.toLowerCase().indexOf(value.toLowerCase()) === 0
        }))
      )
  }

  getFormValidationErrors() {
    Object.keys(this.detailForm.controls).forEach(key => {

      const controlErrors: ValidationErrors = this.detailForm.get(key).errors;
      if (controlErrors != null) {
        Object.keys(controlErrors).forEach(keyError => {
          console.log('Key control: ' + key + ', keyError: ' + keyError + ', err value: ', controlErrors[keyError]);
        });
      }
    });
  }

  saveAppointment() {
    
    if (this.detailForm.valid) {
      let fd = this.detailForm.value;
      fd.symptoms = fd.symptoms && fd.symptoms.length ? fd.symptoms.join(','): "";
      fd.questions = fd.questions.map((value, i) => {
        return {
          question: this.meetingDetail.questions[i].title,
          answer: value
        }
      });

      this.onSubmit.emit(Object.assign({},fd));
    }
  }

}
