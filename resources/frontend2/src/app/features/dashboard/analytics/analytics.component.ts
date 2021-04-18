import { Component, OnInit } from '@angular/core';
import { ChartOptions, ChartType, ChartDataSets } from 'chart.js';
import { Label } from 'ng2-charts';
import { AppointmentService } from 'src/app/core/services/appointment.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { UiService } from 'src/app/core/services/ui.service';
// import * as momentTz from 'moment-timezone';
// import * as moment from 'moment';


@Component({
  selector: 'app-analytics',
  templateUrl: './analytics.component.html',
  styleUrls: ['./analytics.component.scss']
})
export class AnalyticsComponent implements OnInit {
  public appointmentBarChartOptions: ChartOptions = {
    responsive: true,
    scales: { xAxes: [{}], yAxes: [{}] },
  };
  public appointmentBarChartLabels: Label[] = [];
  public appointmentBarChartType: ChartType = 'bar'; //'horizontalBar';
  public appointmentBarChartLegend = true;
  public appointmentBarChartData: ChartDataSets[] = [
    { data: [], label: 'By old patients', backgroundColor: "#90EE90", hoverBackgroundColor:"#90EE90", stack: 'a' },
    { data: [], label: 'By new patients', backgroundColor: "#673AB7", hoverBackgroundColor:"#673AB7"                                                ,stack: 'a' }
  ];

  public patientBarChartOptions: ChartOptions = {
    responsive: true,
    scales: { xAxes: [{}], yAxes: [{
      ticks: {
        beginAtZero: true,
        stepSize: 1
      }
    }] },
  };
  public patientBarChartLabels: Label[] = [];
  public patientBarChartType: ChartType = 'bar'; //'horizontalBar';
  public patientBarChartLegend = true;
  
  public patientBarChartData: ChartDataSets[] = [
    // { data: [], label: 'Old Patients', backgroundColor: "#90EE90", hoverBackgroundColor:"#90EE90", stack: 'a' },
    { data: [], label: 'New Patients', backgroundColor: "#673AB7", hoverBackgroundColor:"#673AB7"                                                ,stack: 'a' }
  ];

  constructor(
    private appointmentService: AppointmentService,
    private notificationService: NotificationService,
    private ui: UiService
  ) { }

  ngOnInit(): void {
    this.fetchStats();
  }

  fetchStats() {
    this.appointmentService.getAppointmentStats().subscribe((data:any) => {
      console.log(data);
      this.appointmentBarChartLabels = data.appointments.labels;
      this.appointmentBarChartData[0].data = data.appointments.old;
      this.appointmentBarChartData[1].data = data.appointments.new;

      this.patientBarChartLabels = data.patients.labels;
      // this.patientBarChartData[0].data = data.patients.old;
      this.patientBarChartData[0].data = data.patients.new;
    });
  }

}
