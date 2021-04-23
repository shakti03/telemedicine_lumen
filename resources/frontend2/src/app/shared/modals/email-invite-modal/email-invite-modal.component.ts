import { Component, OnInit, Input, Output, EventEmitter, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { FormGroup, FormBuilder, Validators, FormArray, FormControl, FormGroupDirective } from '@angular/forms';
import { CommonService } from 'src/app/core/services/common.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';

interface DialogData {
  link: string
}

@Component({
  selector: 'app-email-invite-modal',
  templateUrl: './email-invite-modal.component.html',
  styleUrls: ['./email-invite-modal.component.scss']
})
export class EmailInviteModalComponent implements OnInit {
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();
  emails: Array<string> = [];
  emailForms: FormArray = new FormArray([]);
  addEmailForm: FormGroup;

  constructor(
    public dialogRef: MatDialogRef<EmailInviteModalComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData,
    private ui: UiService,
    private notificationService: NotificationService,
    private commonService: CommonService
  ) { }

  ngOnInit(): void {
    this.addEmailForm = new FormGroup({
      email: new FormControl('')
    })
  }

  addEmail(frm: FormGroup, nativeForm: FormGroupDirective) {
    if (frm.valid && frm.value.email && frm.value.email.trim()) {
      this.emailForms.push(new FormGroup({
        email: new FormControl(frm.value.email, Validators.required)
      }));

      frm.reset();
      nativeForm.resetForm();
    }
  }

  deleteEmail(index: number) {
    this.emailForms.removeAt(index);
  }

  submit() {
    var emails = this.emailForms.value.map((form) => {
      return form.email;
    });

    if(emails.length) {
      this.ui.showSpinner();
      this.commonService.inviteViaEmail({link: this.data.link, emails: emails}).subscribe((result: any) => {
        console.log(result);
        this.notificationService.openSnackBar(result.message);
        this.ui.stopSpinner();
        this.dialogRef.close({ 'status': 'sent' });
      }, err => {
        this.ui.stopSpinner();
        this.notificationService.openSnackBar(err.error ? err.error : (err.message ? err.message : 'Request failed'));
      })
      // this.onSubmit.emit(this.emailForms.value);
    }
  }

}
