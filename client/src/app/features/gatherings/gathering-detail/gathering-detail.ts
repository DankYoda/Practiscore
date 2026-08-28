import { Component, OnInit, computed, inject, signal } from '@angular/core';
import { DatePipe, CurrencyPipe } from '@angular/common';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { forkJoin } from 'rxjs';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatTableModule } from '@angular/material/table';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { relationId } from '../../../core/api/json-ld';
import { Gathering } from '../../../models/gathering';
import { Registration } from '../../../models/registration';
import { Score } from '../../../models/score';
import { GatheringService } from '../gathering.service';
import { RegistrationService } from '../../registrations/registration.service';
import { ScoreService } from '../../scores/score.service';

@Component({
  selector: 'app-gathering-detail',
  imports: [
    RouterLink,
    DatePipe,
    CurrencyPipe,
    MatCardModule,
    MatButtonModule,
    MatIconModule,
    MatTableModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './gathering-detail.html',
  styleUrl: './gathering-detail.css',
})
export class GatheringDetail implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly gatheringService = inject(GatheringService);
  private readonly registrationService = inject(RegistrationService);
  private readonly scoreService = inject(ScoreService);
  protected readonly auth = inject(AuthService);

  protected readonly gathering = signal<Gathering | null>(null);
  protected readonly registrations = signal<Registration[]>([]);
  protected readonly scores = signal<Score[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);

  protected readonly clubId = computed(() => {
    const g = this.gathering();
    return g ? this.gatheringService.clubIdOf(g) : null;
  });

  protected readonly isAlreadyRegistered = computed(() => {
    const userId = this.auth.currentUser()?.id;
    if (!userId) {
      return false;
    }
    return this.registrations().some((r) => relationId(r.user) === userId);
  });

  protected readonly registrationColumns = ['shooter', 'division', 'classification', 'memberNumber'];
  protected readonly scoreColumns = ['shooter', 'alpha', 'charlie', 'delta', 'mike', 'noShoot'];

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id')!;
    this.gatheringService.getById(id).subscribe({
      next: (gathering) => {
        if (!gathering) {
          this.error.set({ status: 404, title: 'Match not found' });
          this.loading.set(false);
          return;
        }
        this.gathering.set(gathering);
        forkJoin({
          registrations: this.registrationService.listForGathering(id),
          scores: this.scoreService.listForGathering(id),
        }).subscribe({
          next: ({ registrations, scores }) => {
            this.registrations.set(registrations);
            this.scores.set(scores);
            this.loading.set(false);
          },
          error: () => {
            // Results/roster may not be available yet; still show the match itself.
            this.loading.set(false);
          },
        });
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }

  relationId = relationId;
}
