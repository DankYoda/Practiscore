import { Routes } from '@angular/router';

export const SCORES_ROUTES: Routes = [
  {
    path: '',
    loadComponent: () => import('./score-search/score-search').then((m) => m.ScoreSearch),
  },
  {
    path: 'gathering/:id',
    loadComponent: () => import('./gathering-scores/gathering-scores').then((m) => m.GatheringScores),
  },
];
