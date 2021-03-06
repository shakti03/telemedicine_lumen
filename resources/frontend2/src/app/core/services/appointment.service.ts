import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

import { appointment as APPOINTMENT_API } from '../constants/api';



const putRequestHttpOptions = {
  headers: new HttpHeaders({
    'Content-Type': 'application/x-www-form-urlencoded'
  })
};

@Injectable({
  providedIn: 'root'
})
export class AppointmentService {

  constructor(private http: HttpClient) { }

  public getAppointmentDetail() {
    return this.http.get(APPOINTMENT_API.get_appointment_detail);
  }

  public updateAppointmentDetail(data: any) {
    data._method = 'put';
    return this.http.post(APPOINTMENT_API.update_appointment_info, data);
  }

  public updateSchedules(data: any) {
    data._method = 'put';
    return this.http.post(APPOINTMENT_API.update_schedules, data);
  }

  public updateQuestions(data: any) {
    data._method = 'put';
    return this.http.post(APPOINTMENT_API.update_questions, data);
  }

  public getAppointments(params?: any) {
    return this.http.get(APPOINTMENT_API.appointments + (params ? '?' + this.queryString(params) : ''));
  }

  public getAppointmentStats() {
    return this.http.get(APPOINTMENT_API.appointment_stats);
  }

  public getEarnings() {
    return this.http.get(APPOINTMENT_API.earnings);
  }

  public getWaitingAppointments(params?: any) {
    return this.http.get(APPOINTMENT_API.waiting_appointments + (params ? '?' + this.queryString(params) : ''));
  }

  public changeAppointmentStatus(appointmentId: string, payload: any) {
    return this.http.post(APPOINTMENT_API.appointment_status(appointmentId), payload);
  }

  public queryString(obj) {
    var str = [];
    for (var p in obj) {
      if (obj.hasOwnProperty(p)) {
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
      }
    }
    return str.join("&");
  }

}
