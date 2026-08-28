import { Routes } from '@angular/router';
import { authGuard } from '../../core/auth/auth.guard';

export const CLUBS_ROUTES: Routes = [
  {
    path: '',
    loadComponent: () => import('./club-list/club-list').then((m) => m.ClubList),
  },
  {
    path: ':id/edit',
    canActivate: [authGuard],
    loadComponent: () => import('./club-edit/club-edit').then((m) => m.ClubEdit),
  },
  {
    path: ':id',
    loadComponent: () => import('./club-detail/club-detail').then((m) => m.ClubDetail),
  },
];
