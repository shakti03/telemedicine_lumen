import { Injectable, Inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs/operators';
import * as jwt_decode from 'jwt-decode';
import * as moment from 'moment';
import 'rxjs/add/operator/delay';

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
            console.log(response);

            this.localStorage.setItem('currentUser', JSON.stringify({
                token: response.api_token,
                email: response.email,
                alias: response.first_name ? response.first_name.split('@')[0] : '',
                expiration: moment().add(1, 'days').toDate(),
                fullName: `${response.first_name} ${response.last_name}`
            }));

            return of(response);
        }));

        // return of(true).delay(1000)
        //     .pipe(map((/*response*/) => {
        //         // set token property
        //         // const decodedToken = jwt_decode(response['token']);

        //         // store email and jwt token in local storage to keep user logged in between page refreshes
        //         this.localStorage.setItem('currentUser', JSON.stringify({
        //             token: 'aisdnaksjdn,axmnczm',
        //             isAdmin: true,
        //             email: 'shaktisingh03@gmail.com',
        //             id: '12312323232',
        //             alias: 'shaktisingh03@gmail.com'.split('@')[0],
        //             expiration: moment().add(1, 'days').toDate(),
        //             fullName: 'Shakti Singh'
        //         }));

        //         return true;
        //     }));
    }

    logout(): void {
        // clear token remove user from local storage to log user out
        this.localStorage.removeItem('currentUser');
    }

    getCurrentUser(): any {
        // TODO: Enable after implementation
        return JSON.parse(this.localStorage.getItem('currentUser'));

        // return {
        //     token: 'aisdnaksjdn,axmnczm',
        //     isAdmin: true,
        //     email: 'shaktisingh03@gmail.com',
        //     id: '12312323232',
        //     alias: 'shaktisingh03@gmail.com'.split('@')[0],
        //     expiration: moment().add(1, 'days').toDate(),
        //     fullName: 'Shakti Singh'
        // };
    }

    passwordResetRequest(email: string) {
        return of(true).delay(1000);
    }

    changePassword(email: string, currentPwd: string, newPwd: string) {
        return of(true).delay(1000);
    }

    passwordReset(email: string, token: string, password: string, confirmPassword: string): any {
        return of(true).delay(1000);
    }
}
