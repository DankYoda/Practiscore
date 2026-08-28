import { Component, OnInit, inject, signal } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { forkJoin } from 'rxjs';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { Gathering } from '../../../models/gathering';
import { MatchType } from '../../../models/match-reference';
import { GatheringService } from '../../gatherings/gathering.service';
import { MatchReferenceService } from '../../gatherings/match-reference.service';
import { RegistrationService } from '../registration.service';

@Component({
  selector: 'app-registration-form',
  imports: [
    RouterLink,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatButtonModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './registration-form.html',
  styleUrl: './registration-form.css',
})
export class RegistrationForm implements OnInit {
  private readonly route = inject(ActivatedRoute);
  private readonly router = inject(Router);
  private readonly gatheringService = inject(GatheringService);
  private readonly matchReferenceService = inject(MatchReferenceService);
  private readonly registrationService = inject(RegistrationService);
  private readonly fb = inject(FormBuilder);

  protected readonly gathering = signal<Gathering | null>(null);
  protected readonly matchType = signal<MatchType | null>(null);
  protected readonly loading = signal(true);
  protected readonly saving = signal(false);
  protected readonly error = signal<ApiError | null>(null);

  protected readonly form = this.fb.nonNullable.group({
    division: ['', Validators.required],
    classification: ['', Validators.required],
    powerFactor: ['MINOR', Validators.required],
    memberNumber: ['', Validators.required],
    category: [0],
  });

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id')!;
    forkJoin({
      gathering: this.gatheringService.getById(id),
      matchTypes: this.matchReferenceService.listMatchTypes(),
    }).subscribe({
      next: ({ gathering, matchTypes }) => {
        if (!gathering) {
          this.error.set({ status: 404, title: 'Match not found' });
          this.loading.set(false);
          return;
        }
        this.gathering.set(gathering);
        this.matchType.set(matchTypes.find((mt) => mt.name === gathering.matchType) ?? null);
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
    if (this.form.invalid || !gathering) {
      return;
    }
    this.saving.set(true);
    this.registrationService.create(gathering.id, this.form.getRawValue()).subscribe({
      next: () => this.router.navigate(['/matches', gathering.id]),
      error: (err) => {
        this.error.set(toApiError(err));
        this.saving.set(false);
      },
    });
  }
}
