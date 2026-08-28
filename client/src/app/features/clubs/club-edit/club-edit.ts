import { Component, OnInit, inject, signal } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { ClubService } from '../club.service';

@Component({
  selector: 'app-club-edit',
  imports: [
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './club-edit.html',
  styleUrl: './club-edit.css',
})
export class ClubEdit implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly router = inject(Router);
  private readonly clubService = inject(ClubService);
  private readonly fb = inject(FormBuilder);

  private clubId = '';
  protected readonly loading = signal(true);
  protected readonly saving = signal(false);
  protected readonly error = signal<ApiError | null>(null);

  protected readonly form = this.fb.nonNullable.group({
    name: ['', Validators.required],
    email: ['', [Validators.required, Validators.email]],
    address: ['', Validators.required],
    socials: [''],
    website: [''],
  });

  ngOnInit(): void {
    this.clubId = this.route.snapshot.paramMap.get('id')!;
    this.clubService.get(this.clubId).subscribe({
      next: (club) => {
        this.form.setValue({
          name: club.name,
          email: club.email,
          address: club.address,
          socials: club.socials,
          website: club.website,
        });
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }

  save(): void {
    if (this.form.invalid) {
      return;
    }
    this.saving.set(true);
    this.clubService.patch(this.clubId, this.form.getRawValue()).subscribe({
      next: () => this.router.navigate(['/clubs', this.clubId]),
      error: (err) => {
        this.error.set(toApiError(err));
        this.saving.set(false);
      },
    });
  }
}
