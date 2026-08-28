import { Component, OnInit, computed, inject, signal } from '@angular/core';
import { DatePipe } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { Gathering } from '../../../models/gathering';
import { GatheringService } from '../gathering.service';

@Component({
  selector: 'app-gathering-list',
  imports: [
    RouterLink,
    DatePipe,
    FormsModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatButtonModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './gathering-list.html',
  styleUrl: './gathering-list.css',
})
export class GatheringList implements OnInit {
  private readonly gatheringService = inject(GatheringService);
  protected readonly auth = inject(AuthService);

  protected readonly gatherings = signal<Gathering[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);

  protected readonly search = signal('');
  protected readonly eventTypeFilter = signal<string | null>(null);

  protected readonly eventTypes = computed(() =>
    Array.from(new Set(this.gatherings().map((g) => g.eventType))).sort(),
  );

  protected readonly filtered = computed(() => {
    const search = this.search().trim().toLowerCase();
    const eventType = this.eventTypeFilter();
    return this.gatherings()
      .filter((g) => !eventType || g.eventType === eventType)
      .filter(
        (g) =>
          !search ||
          g.description.toLowerCase().includes(search) ||
          g.city.toLowerCase().includes(search),
      )
      .sort((a, b) => a.startDate.localeCompare(b.startDate));
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
