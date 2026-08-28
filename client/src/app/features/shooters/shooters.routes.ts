import { Routes } from '@angular/router';

export const SHOOTERS_ROUTES: Routes = [
  {
    path: '',
    pathMatch: 'full',
    loadComponent: () => import('./shooter-list/shooter-list').then((m) => m.ShooterList),
  },
  {
    path: ':id',
    loadComponent: () => import('./shooter-profile/shooter-profile').then((m) => m.ShooterProfile),
  },
];
