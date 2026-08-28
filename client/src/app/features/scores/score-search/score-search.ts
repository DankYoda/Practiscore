import { Component, OnInit, computed, inject, signal } from '@angular/core';
import { DatePipe } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatListModule } from '@angular/material/list';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { Gathering } from '../../../models/gathering';
import { GatheringService } from '../../gatherings/gathering.service';

@Component({
  selector: 'app-score-search',
  imports: [
    RouterLink,
    DatePipe,
    FormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatListModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './score-search.html',
  styleUrl: './score-search.css',
})
export class ScoreSearch implements OnInit {
  private readonly gatheringService = inject(GatheringService);

  protected readonly gatherings = signal<Gathering[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);
  protected readonly search = signal('');

  protected readonly filtered = computed(() => {
    const search = this.search().trim().toLowerCase();
    return this.gatherings()
      .filter((g) => !search || g.description.toLowerCase().includes(search) || g.city.toLowerCase().includes(search))
      .sort((a, b) => b.startDate.localeCompare(a.startDate));
  });

  ngOnInit(): void {
    this.gatheringService.list().subscribe({
      next: (gatherings) => {
        this.gatherings.set(gatherings);
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }
}
