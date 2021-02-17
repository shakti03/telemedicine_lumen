import { Component, Input, OnInit, Output, EventEmitter } from '@angular/core';


@Component({
  selector: 'edit-collect-payment',
  templateUrl: './collect-payment.component.html',
  styleUrls: ['./collect-payment.component.scss']
})
export class CollectPaymentComponent implements OnInit {
  @Input() appointmentDetail;
  @Output() onSubmit: EventEmitter<any> = new EventEmitter();
  accept_payment: boolean;
  physician_fee: number = 0;

  constructor() { }

  ngOnInit(): void {
    if (this.appointmentDetail) {
      this.accept_payment = this.appointmentDetail.accept_payment;
      this.physician_fee = this.appointmentDetail.physician_fee;
    }
  }

  submit() {

    this.onSubmit.emit({
      accept_payment: this.accept_payment,
      physician_fee: this.accept_payment ? this.physician_fee : 0
    });

  }
}
