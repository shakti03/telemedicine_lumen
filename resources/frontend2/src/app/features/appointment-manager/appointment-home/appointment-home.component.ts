import { Component, OnInit, Output, EventEmitter, Input } from '@angular/core';

@Component({
  selector: 'app-appointment-home',
  templateUrl: './appointment-home.component.html',
  styleUrls: ['./appointment-home.component.scss']
})
export class AppointmentHomeComponent implements OnInit {
  @Output() edit: EventEmitter<any> = new EventEmitter();
  @Input() meeting: any;

  constructor() { }

  ngOnInit(): void {
  }

}
