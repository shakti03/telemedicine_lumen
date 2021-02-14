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
    return this.http.put(PROFILE_API.profile, data);
  }


}
