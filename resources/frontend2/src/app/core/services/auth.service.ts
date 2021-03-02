import { Injectable, Inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs/operators';
import * as jwt_decode from 'jwt-decode';
import * as moment from 'moment';
// import 'rxjs/add/operator/delay';

import { environment } from '../../../environments/environment';
import { of, EMPTY } from 'rxjs';

import { auth as AUTH_API } from '../constants/api';

@Injectable({
    providedIn: 'root'
})
export class AuthenticationService {

    constructor(private http: HttpClient,
        @Inject('LOCALSTORAGE') private localStorage: Storage) {
    }

    login(email: string, password: string) {

        return this.http.post(AUTH_API.login, { email, password }).pipe(map((response: any) => {
            this.localStorage.setItem('currentUser', JSON.stringify({
                token: response.api_token,
                email: response.email,
                alias: response.first_name ? response.first_name.split('@')[0] : '',
                expiration: moment().add(30, 'days').toDate(),
                fullName: `${response.first_name} ${response.last_name}`.trim(),
                first_name: response.first_name,
                last_name: response.last_name,
                phone: response.phone,
                room_name: response.room_name
            }));

            return of(response);
        }));
    }

    updateUser(response: any) {
        let user = JSON.parse(this.localStorage.getItem('currentUser'));

        user.alias = response.first_name ? response.first_name.split('@')[0] : '';
        user.expiration = moment().add(30, 'days').toDate();
        user.fullName = `${response.first_name} ${response.last_name}`.trim();
        user.first_name = response.first_name;
        user.last_name = response.last_name;
        user.phone = response.phone;
        user.room_name = response.room_name;

        this.localStorage.setItem('currentUser', JSON.stringify(user));
    }

    logout(): void {
        this.localStorage.removeItem('currentUser');
    }

    getCurrentUser(): any {
        return JSON.parse(this.localStorage.getItem('currentUser'));
    }

    passwordResetRequest(email: string) {
        return of(true);//.delay(1000);
    }

    changePassword(email: string, currentPwd: string, newPwd: string) {
        return of(true);//.delay(1000);
    }

    passwordReset(email: string, token: string, password: string, confirmPassword: string): any {
        return of(true);//.delay(1000);
    }
}
