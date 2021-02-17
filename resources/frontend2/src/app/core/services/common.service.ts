import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { common as COMMON_API } from '../constants/api';

@Injectable({
  providedIn: 'root'
})
export class CommonService {

  constructor(private http: HttpClient) { }

  public getSypmtoms() {
    return this.http.get(COMMON_API.get_symptoms);
  }
}
