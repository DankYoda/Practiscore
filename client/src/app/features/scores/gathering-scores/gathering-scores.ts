import { Component, OnInit, inject, signal } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { forkJoin } from 'rxjs';
import { MatTableModule } from '@angular/material/table';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { relationId } from '../../../core/api/json-ld';
import { Gathering } from '../../../models/gathering';
import { Score } from '../../../models/score';
import { GatheringService } from '../../gatherings/gathering.service';
import { ScoreService } from '../score.service';

@Component({
  selector: 'app-gathering-scores',
  imports: [RouterLink, MatTableModule, ErrorMessage, LoadingSpinner],
  templateUrl: './gathering-scores.html',
  styleUrl: './gathering-scores.css',
})
export class GatheringScores implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly gatheringService = inject(GatheringService);
  private readonly scoreService = inject(ScoreService);

  protected readonly gathering = signal<Gathering | null>(null);
  protected readonly scores = signal<Score[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);
  protected readonly columns = ['shooter', 'alpha', 'charlie', 'delta', 'mike', 'noShoot'];

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id')!;
    forkJoin({
      gathering: this.gatheringService.getById(id),
      scores: this.scoreService.listForGathering(id),
    }).subscribe({
      next: ({ gathering, scores }) => {
        if (!gathering) {
          this.error.set({ status: 404, title: 'Match not found' });
          this.loading.set(false);
          return;
        }
        this.gathering.set(gathering);
        this.scores.set(scores);
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }

  relationId = relationId;
}
