import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { profile as PROFILE_API } from '../constants/api';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(private http: HttpClient) { }

  public getProfile() {
    return this.http.get(PROFILE_API.profile);
  }

  public updateProfile(data: any) {
    data._method = 'put';
    return this.http.post(PROFILE_API.update_profile, data);
  }

  public updatePassword(data: any) {
    data._method = 'put';
    return this.http.post(PROFILE_API.update_password, data);
  }

}