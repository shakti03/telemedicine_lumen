import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthGuard } from './core/guards/auth.guard';

const appRoutes: Routes = [
    {
        path: 'auth',
        loadChildren: () => import('./features/auth/auth.module').then(m => m.AuthModule),
    },
    {
        path: 'p/:user_url',
        loadChildren: () => import('./features/physician-scheduler/physician-scheduler.module').then(m => m.PhysicianSchedulerModule),
    },
    {
        path: 'dashboard',
        loadChildren: () => import('./features/dashboard/dashboard.module').then(m => m.DashboardModule),
        canActivate: [AuthGuard]
    },
    {
        path: 'appointment-manager',
        loadChildren: () => import('./features/appointment-manager/appointment-manager.module').then(m => m.AppointmentManagerModule),
        canActivate: [AuthGuard]
    },
    {
        path: 'account',
        loadChildren: () => import('./features/settings/settings.module').then(m => m.SettingsModule),
        canActivate: [AuthGuard]
    },
    {
        path: '**',
        redirectTo: 'dashboard',
        pathMatch: 'full'
    }
];

@NgModule({
    imports: [
        RouterModule.forRoot(appRoutes, { relativeLinkResolution: 'legacy' })
    ],
    exports: [RouterModule],
    providers: []
})
export class AppRoutingModule { }
