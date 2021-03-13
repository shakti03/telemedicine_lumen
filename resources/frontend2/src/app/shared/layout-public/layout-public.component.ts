import { Component, OnInit, ChangeDetectorRef, OnDestroy, AfterViewInit } from '@angular/core';
import { MediaMatcher } from '@angular/cdk/layout';
import { TimerObservable } from 'rxjs/observable/TimerObservable';
import { Subscription } from 'rxjs';

import { environment } from './../../../environments/environment';
import { AuthenticationService } from './../../core/services/auth.service';
import { SpinnerService } from '../../core/services/spinner.service';
import { AuthGuard } from 'src/app/core/guards/auth.guard';

@Component({
  selector: 'app-layout-public',
  templateUrl: './layout-public.component.html',
  styleUrls: ['./layout-public.component.scss']
})
export class LayoutPublicComponent implements OnInit, OnDestroy, AfterViewInit {

  private _mobileQueryListener: () => void;
  mobileQuery: MediaQueryList;
  // showSpinner: boolean = false;
  userName: string = '';
  // isAdmin: boolean = false;
  app_name: string = environment.app_name;

  private autoLogoutSubscription!: Subscription;

  constructor(private changeDetectorRef: ChangeDetectorRef,
    private media: MediaMatcher,
    public spinnerService: SpinnerService,
    private authService: AuthenticationService,
    private authGuard: AuthGuard) {

    this.mobileQuery = this.media.matchMedia('(max-width: 1000px)');
    this._mobileQueryListener = () => changeDetectorRef.detectChanges();
    // tslint:disable-next-line: deprecation
    this.mobileQuery.addListener(this._mobileQueryListener);
  }

  ngOnInit(): void {

  }

  ngOnDestroy(): void {
    // tslint:disable-next-line: deprecation
    this.mobileQuery.removeListener(this._mobileQueryListener);
  }

  ngAfterViewInit(): void {
    this.changeDetectorRef.detectChanges();
  }

}
