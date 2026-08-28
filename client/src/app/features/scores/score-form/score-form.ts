import { Component, OnInit, inject, signal } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { forkJoin } from 'rxjs';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { Gathering } from '../../../models/gathering';
import { GatheringService } from '../../gatherings/gathering.service';
import { ScoreService } from '../score.service';

@Component({
  selector: 'app-score-form',
  imports: [
    RouterLink,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './score-form.html',
  styleUrl: './score-form.css',
})
export class ScoreForm implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly router = inject(Router);
  private readonly gatheringService = inject(GatheringService);
  private readonly scoreService = inject(ScoreService);
  private readonly auth = inject(AuthService);
  private readonly fb = inject(FormBuilder);

  protected readonly gathering = signal<Gathering | null>(null);
  protected readonly loading = signal(true);
  protected readonly saving = signal(false);
  protected readonly error = signal<ApiError | null>(null);
  private existingScoreId: string | null = null;

  protected readonly form = this.fb.nonNullable.group({
    alpha: [0, [Validators.required, Validators.min(0)]],
    charlie: [0, [Validators.required, Validators.min(0)]],
    delta: [0, [Validators.required, Validators.min(0)]],
    mike: [0, [Validators.required, Validators.min(0)]],
    noShoot: [0, [Validators.required, Validators.min(0)]],
  });

  ngOnInit(): void {
    const gatheringId = this.route.snapshot.paramMap.get('id')!;
    const userId = this.auth.currentUser()?.id;
    if (!userId) {
      this.loading.set(false);
      return;
    }

    forkJoin({
      gathering: this.gatheringService.getById(gatheringId),
      scores: this.scoreService.listForUserAtGathering(gatheringId, userId),
    }).subscribe({
      next: ({ gathering, scores }) => {
        if (!gathering) {
          this.error.set({ status: 404, title: 'Match not found' });
          this.loading.set(false);
          return;
        }
        this.gathering.set(gathering);
        const existing = scores[0];
        if (existing) {
          this.existingScoreId = existing.id;
          this.form.setValue({
            alpha: existing.alpha,
            charlie: existing.charlie,
            delta: existing.delta,
            mike: existing.mike,
            noShoot: existing.noShoot,
          });
        }
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }

  submit(): void {
    const gathering = this.gathering();
    const userId = this.auth.currentUser()?.id;
    if (this.form.invalid || !gathering || !userId) {
      return;
    }
    this.saving.set(true);
    const value = this.form.getRawValue();
    const request = this.existingScoreId
      ? this.scoreService.patch(gathering.id, userId, this.existingScoreId, value)
      : this.scoreService.create(gathering.id, userId, value);

    request.subscribe({
      next: () => this.router.navigate(['/matches', gathering.id]),
      error: (err) => {
        this.error.set(toApiError(err));
        this.saving.set(false);
      },
    });
  }
}
