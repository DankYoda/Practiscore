import { Component, OnInit, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { MatCardModule } from '@angular/material/card';
import { MatIconModule } from '@angular/material/icon';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { Club } from '../../../models/club';
import { ClubService } from '../club.service';

@Component({
  selector: 'app-club-list',
  imports: [RouterLink, MatCardModule, MatIconModule, ErrorMessage, LoadingSpinner],
  templateUrl: './club-list.html',
  styleUrl: './club-list.css',
})
export class ClubList implements OnInit {
  private readonly clubService = inject(ClubService);

  protected readonly clubs = signal<Club[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);

  ngOnInit(): void {
    this.clubService.list().subscribe({
      next: (clubs) => {
        this.clubs.set(clubs);
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }
}
