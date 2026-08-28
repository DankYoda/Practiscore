import { Component, OnInit, inject, signal } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { MatchType } from '../../../models/match-reference';
import { GatheringService } from '../gathering.service';
import { MatchReferenceService } from '../match-reference.service';

@Component({
  selector: 'app-gathering-form',
  imports: [
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatButtonModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './gathering-form.html',
  styleUrl: './gathering-form.css',
})
export class GatheringForm implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly router = inject(Router);
  private readonly gatheringService = inject(GatheringService);
  private readonly matchReferenceService = inject(MatchReferenceService);
  private readonly auth = inject(AuthService);
  private readonly fb = inject(FormBuilder);

  protected editingId: string | null = null;
  protected readonly loading = signal(true);
  protected readonly saving = signal(false);
  protected readonly error = signal<ApiError | null>(null);
  protected readonly matchTypes = signal<MatchType[]>([]);

  protected readonly form = this.fb.nonNullable.group({
    description: ['', Validators.required],
    address: ['', Validators.required],
    city: ['', Validators.required],
    state: ['', Validators.required],
    country: ['', Validators.required],
    openDate: ['', Validators.required],
    closeDate: ['', Validators.required],
    startDate: ['', Validators.required],
    endDate: ['', Validators.required],
    waiver: [''],
    eventType: ['', Validators.required],
    matchType: ['', Validators.required],
  });

  ngOnInit(): void {
    this.editingId = this.route.snapshot.paramMap.get('id');

    this.matchReferenceService.listMatchTypes().subscribe((types) => this.matchTypes.set(types));

    if (!this.editingId) {
      this.loading.set(false);
      return;
    }

    this.gatheringService.getById(this.editingId).subscribe({
      next: (gathering) => {
        if (!gathering) {
          this.error.set({ status: 404, title: 'Match not found' });
          this.loading.set(false);
          return;
        }
        this.form.setValue({
          description: gathering.description,
          address: gathering.address,
          city: gathering.city,
          state: gathering.state,
          country: gathering.country,
          openDate: gathering.openDate.slice(0, 10),
          closeDate: gathering.closeDate.slice(0, 10),
          startDate: gathering.startDate.slice(0, 10),
          endDate: gathering.endDate.slice(0, 10),
          waiver: gathering.waiver,
          eventType: gathering.eventType,
          matchType: gathering.matchType,
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
    const clubId = this.auth.managedClubId();
    if (this.form.invalid || !clubId) {
      return;
    }
    this.saving.set(true);
    const value = this.form.getRawValue();
    const request = this.editingId
      ? this.gatheringService.patch(clubId, this.editingId, value)
      : this.gatheringService.create(clubId, value);

    request.subscribe({
      next: (gathering) => this.router.navigate(['/matches', gathering.id]),
      error: (err) => {
        this.error.set(toApiError(err));
        this.saving.set(false);
      },
    });
  }
}
