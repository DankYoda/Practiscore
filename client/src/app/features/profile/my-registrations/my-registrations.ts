import { Component, OnInit, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatExpansionModule } from '@angular/material/expansion';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { relationId } from '../../../core/api/json-ld';
import { Registration, RegistrationWriteInput } from '../../../models/registration';
import { RegistrationService } from '../../registrations/registration.service';

@Component({
  selector: 'app-my-registrations',
  imports: [
    RouterLink,
    ReactiveFormsModule,
    MatExpansionModule,
    MatButtonModule,
    MatFormFieldModule,
    MatInputModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './my-registrations.html',
  styleUrl: './my-registrations.css',
})
export class MyRegistrations implements OnInit {
  private readonly auth = inject(AuthService);
  private readonly registrationService = inject(RegistrationService);
  private readonly fb = inject(FormBuilder);

  protected readonly registrations = signal<Registration[]>([]);
  protected readonly loading = signal(true);
  protected readonly error = signal<ApiError | null>(null);
  protected readonly savingId = signal<string | null>(null);

  private readonly forms = new Map<string, FormGroup>();

  ngOnInit(): void {
    const userId = this.auth.currentUser()?.id;
    if (!userId) {
      this.loading.set(false);
      return;
    }
    this.registrationService.listForUser(userId).subscribe({
      next: (registrations) => {
        this.registrations.set(registrations);
        for (const r of registrations) {
          this.forms.set(
            r.id,
            this.fb.group({
              division: [r.division, Validators.required],
              classification: [r.classification, Validators.required],
              powerFactor: [r.powerFactor, Validators.required],
              memberNumber: [r.memberNumber, Validators.required],
              category: [r.category],
            }),
          );
        }
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }

  formFor(registrationId: string): FormGroup {
    return this.forms.get(registrationId)!;
  }

  gatheringId(registration: Registration): string | null {
    return relationId(registration.gathering);
  }

  save(registration: Registration): void {
    const userId = this.auth.currentUser()?.id;
    const form = this.forms.get(registration.id);
    if (!userId || !form || form.invalid) {
      return;
    }
    this.savingId.set(registration.id);
    const value = form.getRawValue() as RegistrationWriteInput;
    this.registrationService.patch(userId, registration.id, value).subscribe({
      next: (updated) => {
        this.registrations.update((list) =>
          list.map((r) => (r.id === updated.id ? updated : r)),
        );
        this.savingId.set(null);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.savingId.set(null);
      },
    });
  }

  cancel(registration: Registration): void {
    const userId = this.auth.currentUser()?.id;
    if (!userId) {
      return;
    }
    this.registrationService.cancel(userId, registration.id).subscribe({
      next: () => {
        this.registrations.update((list) => list.filter((r) => r.id !== registration.id));
        this.forms.delete(registration.id);
      },
      error: (err) => this.error.set(toApiError(err)),
    });
  }
}
