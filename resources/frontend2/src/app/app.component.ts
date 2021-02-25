import { Component, OnInit } from '@angular/core';
import { Router, RouteConfigLoadStart, RouteConfigLoadEnd } from '@angular/router';
import { UiService } from './core/services/ui.service';

@Component({
  selector: 'app-root',
  template: `<router-outlet></router-outlet>`
})
export class AppComponent implements OnInit {
  constructor(private router: Router, private ui: UiService) { }

  ngOnInit() {
    this.router.events.subscribe(event => {
      if (event instanceof RouteConfigLoadStart) {
        this.ui.showSpinner();
      } else if (event instanceof RouteConfigLoadEnd) {
        this.ui.stopSpinner();
      }
    });
  }
}
