import { Component, OnInit, inject, signal } from '@angular/core';
import { DatePipe } from '@angular/common';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { forkJoin } from 'rxjs';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { Club } from '../../../models/club';
import { Gathering } from '../../../models/gathering';
import { ClubService } from '../club.service';
import { GatheringService } from '../../gatherings/gathering.service';

@Component({
  selector: 'app-club-detail',
  imports: [
    RouterLink,
    DatePipe,
    MatCardModule,
    MatButtonModule,
    MatIconModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './club-detail.html',
  styleUrl: './club-detail.css',
})
export class ClubDetail implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly clubService = inject(ClubService);
  private readonly gatheringService = inject(GatheringService);
  protected readonly auth = inject(AuthService);

  protected readonly club = signal<Club | null>(null);
  protected readonly gatherings = signal<Gathering[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id')!;
    forkJoin({
      club: this.clubService.get(id),
      gatherings: this.gatheringService.list(),
    }).subscribe({
      next: ({ club, gatherings }) => {
        this.club.set(club);
        this.gatherings.set(gatherings.filter((g) => this.gatheringService.clubIdOf(g) === id));
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }
}
