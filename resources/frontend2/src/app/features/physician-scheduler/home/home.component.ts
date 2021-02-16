import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'physician-scheduler-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  showTimeSlots: boolean = false;
  constructor() { }

  ngOnInit(): void {
    console.log('here loaded');
  }

  onSelect(event: any) {
    this.showTimeSlots = true;
  }

}
