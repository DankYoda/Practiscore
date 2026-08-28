import { Routes } from '@angular/router';
import { authGuard } from '../../core/auth/auth.guard';

export const GATHERINGS_ROUTES: Routes = [
  {
    path: '',
    loadComponent: () => import('./gathering-list/gathering-list').then((m) => m.GatheringList),
  },
  {
    path: 'new',
    canActivate: [authGuard],
    loadComponent: () => import('./gathering-form/gathering-form').then((m) => m.GatheringForm),
  },
  {
    path: ':id/edit',
    canActivate: [authGuard],
    loadComponent: () => import('./gathering-form/gathering-form').then((m) => m.GatheringForm),
  },
  {
    path: ':id/score',
    canActivate: [authGuard],
    loadComponent: () => import('../scores/score-form/score-form').then((m) => m.ScoreForm),
  },
  {
    path: ':id',
    loadComponent: () => import('./gathering-detail/gathering-detail').then((m) => m.GatheringDetail),
  },
];
