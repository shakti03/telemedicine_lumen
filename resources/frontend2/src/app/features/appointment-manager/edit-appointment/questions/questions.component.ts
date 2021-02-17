import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';

@Component({
  selector: 'edit-questions',
  templateUrl: './questions.component.html',
  styleUrls: ['./questions.component.scss']
})
export class QuestionsComponent implements OnInit {
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();
  @Input() questions;
  questionForms: FormArray = new FormArray([]);
  addQuestionForm: FormGroup;

  constructor() { }

  ngOnInit(): void {
    this.addQuestionForm = new FormGroup({
      title: new FormControl('')
    })

    if (this.questions) {
      this.questions.forEach(question => {
        this.questionForms.push(new FormGroup({
          title: new FormControl(question.title, Validators.required)
        }));
      });
    }
  }

  addQuestion(frm: FormGroup, nativeForm: FormGroupDirective) {
    if (frm.valid && frm.value.title && frm.value.title.trim()) {
      this.questionForms.push(new FormGroup({
        title: new FormControl(frm.value.title, Validators.required)
      }));

      frm.reset();
      nativeForm.resetForm();
    }
  }

  deleteQuestion(index: number) {
    this.questionForms.removeAt(index);
  }

  submit() {
    this.onSubmit.emit(this.questionForms.value);
  }

}
