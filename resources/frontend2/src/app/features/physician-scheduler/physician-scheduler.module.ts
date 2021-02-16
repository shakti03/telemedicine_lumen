import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PhysicianSchedulerRoutingModule } from './physician-scheduler-routing.module';
import { SharedModule } from '../../shared/shared.module';

import { HomeComponent } from './home/home.component';


@NgModule({
  declarations: [HomeComponent],
  imports: [
    CommonModule,
    PhysicianSchedulerRoutingModule,
    SharedModule
  ]
})
export class PhysicianSchedulerModule { }
