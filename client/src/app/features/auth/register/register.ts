import { Component, inject, signal } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';

@Component({
  selector: 'app-register',
  imports: [
    RouterLink,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatCardModule,
    ErrorMessage,
  ],
  templateUrl: './register.html',
  styleUrl: './register.css',
})
export class Register {
  private readonly auth = inject(AuthService);
  private readonly router = inject(Router);
  private readonly fb = inject(FormBuilder);

  protected readonly submitting = signal(false);
  protected readonly error = signal<ApiError | null>(null);

  protected readonly form = this.fb.nonNullable.group({
    username: ['', Validators.required],
    firstName: ['', Validators.required],
    lastName: ['', Validators.required],
    email: ['', [Validators.required, Validators.email]],
    plainPassword: ['', [Validators.required, Validators.minLength(14)]],
  });

  submit(): void {
    if (this.form.invalid) {
      return;
    }
    this.submitting.set(true);
    this.error.set(null);
    const { username, firstName, lastName, email, plainPassword } = this.form.getRawValue();

    this.auth
      .register({
        user: {
          username,
          firstName,
          lastName,
          email,
          titles: '',
          guns: '',
          optics: '',
          ammo: '',
          instagramHandle: '',
          bio: '',
        },
        plainPassword,
      })
      .subscribe({
        next: () => {
          this.auth.login(username, plainPassword).subscribe({
            next: () => this.router.navigate(['/profile']),
            error: () => this.router.navigate(['/login']),
          });
        },
        error: (err) => {
          this.error.set(toApiError(err));
          this.submitting.set(false);
        },
      });
  }
}
