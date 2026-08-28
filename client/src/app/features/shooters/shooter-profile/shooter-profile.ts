import { Component, OnInit, inject, signal } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { MatCardModule } from '@angular/material/card';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { relationId } from '../../../core/api/json-ld';
import { UserPublicProfile } from '../../../models/user';
import { ShooterService } from '../shooter.service';

@Component({
  selector: 'app-shooter-profile',
  imports: [RouterLink, MatCardModule, ErrorMessage, LoadingSpinner],
  templateUrl: './shooter-profile.html',
  styleUrl: './shooter-profile.css',
})
export class ShooterProfile implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly shooterService = inject(ShooterService);

  protected readonly profile = signal<UserPublicProfile | null>(null);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id')!;
    this.shooterService.get(id).subscribe({
      next: (profile) => {
        this.profile.set(profile);
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
