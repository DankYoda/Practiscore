import { Routes } from '@angular/router';
import { authGuard } from './app/core/auth/auth.guard';

export const routes: Routes = [
  {
    path: '',
    pathMatch: 'full',
    redirectTo: 'matches',
  },
  {
    path: '',
    loadChildren: () => import('./app/features/auth/auth.routes').then((m) => m.AUTH_ROUTES),
  },
  {
    path: 'clubs',
    loadChildren: () => import('./app/features/clubs/clubs.routes').then((m) => m.CLUBS_ROUTES),
  },
  {
    path: 'matches',
    loadChildren: () =>
      import('./app/features/gatherings/gatherings.routes').then((m) => m.GATHERINGS_ROUTES),
  },
  {
    path: 'scores',
    loadChildren: () => import('./app/features/scores/scores.routes').then((m) => m.SCORES_ROUTES),
  },
  {
    path: 'shooters',
    loadChildren: () =>
      import('./app/features/shooters/shooters.routes').then((m) => m.SHOOTERS_ROUTES),
  },
  {
    path: 'profile',
    canActivate: [authGuard],
    loadComponent: () => import('./app/features/profile/profile').then((m) => m.Profile),
  },
  {
    path: '**',
    loadComponent: () => import('./app/core/layout/not-found/not-found').then((m) => m.NotFound),
  },
];
