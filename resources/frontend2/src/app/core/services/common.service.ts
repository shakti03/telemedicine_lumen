import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { general as PUBLIC_API } from '../constants/api';

@Injectable({
  providedIn: 'root'
})
export class CommonService {

  constructor(private http: HttpClient) { }

  public getSypmtoms(term: string) {
    return this.http.get(PUBLIC_API.get_symptoms+`?term=${term}`);
  }


  public getPhysicianMeetingDetail($physicianLink: String) {
    return this.http.get(PUBLIC_API.get_physician_appointment_detail($physicianLink));
  }

  public createAppointment(data: any) {
    return this.http.post(PUBLIC_API.create_appointment, data);
  }

  public inviteViaEmail(data: any) {
    return this.http.post(PUBLIC_API.email_invite, data);
  }
}
