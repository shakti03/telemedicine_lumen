import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AccountModule } from './features/account/account.module';
import { AuthGuard } from './core/guards/auth.guard';

const appRoutes: Routes = [
    {
        path: 'auth',
        loadChildren: () => import('./features/auth/auth.module').then(m => m.AuthModule),
        // loadChildren: './auth/auth.module#AuthModule'
    },
    {
        path: 'dashboard',
        loadChildren: () => import('./features/dashboard/dashboard.module').then(m => m.DashboardModule),
        canActivate: [AuthGuard]
    },
    {
        path: 'appointment-manager',
        loadChildren: () => import('./features/appointment-manager/appointment-manager.module').then(m => m.CustomersModule),
        // loadChildren: './customers/customers.module#CustomersModule',
        canActivate: [AuthGuard]
    },
    {
        path: 'account',
        loadChildren: () => import('./features/settings/settings.module').then(m => m.SettingsModule),
        // loadChildren: './users/users.module#UsersModule',
        canActivate: [AuthGuard]
    },
    {
        path: 'account2',
        loadChildren: () => import('./features/account/account.module').then(m => m.AccountModule),
        // loadChildren: './features/account/account.module#AccountModule',
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
