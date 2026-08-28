import { Routes } from '@angular/router';

export const SHOOTERS_ROUTES: Routes = [
  {
    path: ':id',
    loadComponent: () => import('./shooter-profile/shooter-profile').then((m) => m.ShooterProfile),
  },
];
