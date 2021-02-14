import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { appointment as APPOINTMENT_API } from '../constants/api';

@Injectable({
  providedIn: 'root'
})
export class AppointmentService {

  constructor(private http: HttpClient) { }

  public getAppointmentDetail() {
    return this.http.get(APPOINTMENT_API.get_appointment_detail);
  }

  public updateAppointmentDetail(data: any) {
    return this.http.get(APPOINTMENT_API.update_appointment_info, data);
  }

  public updateSchedules(data: any) {
    return this.http.put(APPOINTMENT_API.update_scheules, data);
  }

  public updateQuestions(data: any) {
    return this.http.put(APPOINTMENT_API.update_questions, data);
  }

}
