import { Component, OnInit, computed, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { UserPublicProfile } from '../../../models/user';
import { ShooterService } from '../shooter.service';

@Component({
  selector: 'app-shooter-list',
  imports: [
    RouterLink,
    FormsModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './shooter-list.html',
  styleUrl: './shooter-list.css',
})
export class ShooterList implements OnInit {
  private readonly shooterService = inject(ShooterService);

  protected readonly shooters = signal<UserPublicProfile[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);

  protected readonly search = signal('');

  protected readonly filtered = computed(() => {
    const search = this.search().trim().toLowerCase();
    return this.shooters()
      .filter(
        (s) =>
          !search ||
          s.username.toLowerCase().includes(search) ||
          `${s.firstName} ${s.lastName}`.toLowerCase().includes(search),
      )
      .sort((a, b) => a.username.localeCompare(b.username));
  });

  ngOnInit(): void {
    this.shooterService.list().subscribe({
      next: (shooters) => {
        this.shooters.set(shooters);
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }
}
