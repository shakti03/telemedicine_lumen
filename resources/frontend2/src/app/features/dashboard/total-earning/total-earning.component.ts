import { Component, OnInit } from '@angular/core';
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service'

@Component({
  selector: 'app-total-earning',
  templateUrl: './total-earning.component.html',
  styleUrls: ['./total-earning.component.scss']
})
export class TotalEarningComponent implements OnInit {
  totalEarnings: string = '0.00';
  constructor(
    private appointmentService: AppointmentService,
    private notificationService: NotificationService,
    private ui: UiService
  ) { }

  ngOnInit(): void {
    this.fetchEarnings();
  }

  fetchEarnings() {
    this.appointmentService.getEarnings().subscribe((data: any) => {
      this.totalEarnings = data.total;
    }, err => {
      this.notificationService.openSnackBar(err.message);
    })
    
  }

}
