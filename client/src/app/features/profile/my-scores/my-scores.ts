import { Component, OnInit, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { forkJoin, map, of, switchMap } from 'rxjs';
import { MatTableModule } from '@angular/material/table';
import { MatButtonModule } from '@angular/material/button';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { relationId } from '../../../core/api/json-ld';
import { Score } from '../../../models/score';
import { RegistrationService } from '../../registrations/registration.service';
import { ScoreService } from '../../scores/score.service';

interface MyScoreRow {
  gatheringId: string;
  scores: Score[];
}

@Component({
  selector: 'app-my-scores',
  imports: [RouterLink, MatTableModule, MatButtonModule, ErrorMessage, LoadingSpinner],
  templateUrl: './my-scores.html',
  styleUrl: './my-scores.css',
})
export class MyScores implements OnInit {
  private readonly auth = inject(AuthService);
  private readonly registrationService = inject(RegistrationService);
  private readonly scoreService = inject(ScoreService);

  protected readonly rows = signal<MyScoreRow[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);

  ngOnInit(): void {
    const userId = this.auth.currentUser()?.id;
    if (!userId) {
      this.loading.set(false);
      return;
    }

    this.registrationService
      .listForUser(userId)
      .pipe(
        switchMap((registrations) => {
          const gatheringIds = Array.from(
            new Set(registrations.map((r) => relationId(r.gathering)).filter((id): id is string => !!id)),
          );
          if (gatheringIds.length === 0) {
            return of([] as MyScoreRow[]);
          }
          return forkJoin(
            gatheringIds.map((gatheringId) =>
              this.scoreService
                .listForUserAtGathering(gatheringId, userId)
                .pipe(map((scores) => ({ gatheringId, scores }))),
            ),
          );
        }),
      )
      .subscribe({
        next: (rows) => {
          this.rows.set(rows);
          this.loading.set(false);
        },
        error: (err) => {
          this.error.set(toApiError(err));
          this.loading.set(false);
        },
      });
  }
}
