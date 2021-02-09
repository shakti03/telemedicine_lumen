import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [];

// const routes: Routes = [
//   // { path: '', redirectTo: '/home', pathMatch: 'full'},
//   { path: '', component: HomeComponent },
//   { path: 'login', component: LoginComponent },
//   { path: 'signup', component: SignupComponent },
//   { path: 'dashboard', component: DashboardComponent },
//   { path: 'settings', component: SettingsComponent },
//   { path: 'appointment-manager', component: AppointmentManagerComponent },
//   { path: ':user_url', component: PatientSchedulerComponent },
//   { path: ':user_url/book', component: PatientScheduler2Component },
//   { path: ':user_url/scheduleEventConfirm', component: ScheduleEventConfirm },

//   { path: 'appointment-manager/event-types/30-minutes/edit', component: EditAppointmentManagerComponent },
//   {
//      path: 'search',
//      component: SearchComponent
//   }, {
//      path: 'new-cmp',
//      component: NewCmpComponent
//   },
//   { path: '**', component: ErrorpageComponent }
// ])

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }


