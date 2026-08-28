import { Component, OnInit, inject, signal } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { MatSnackBar } from '@angular/material/snack-bar';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { UserProfileService } from '../user-profile.service';

@Component({
  selector: 'app-profile-details',
  imports: [
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatCardModule,
    ErrorMessage,
  ],
  templateUrl: './profile-details.html',
  styleUrl: './profile-details.css',
})
export class ProfileDetails implements OnInit {
  private readonly auth = inject(AuthService);
  private readonly userProfileService = inject(UserProfileService);
  private readonly fb = inject(FormBuilder);
  private readonly snackBar = inject(MatSnackBar);

  protected readonly savingDetails = signal(false);
  protected readonly detailsError = signal<ApiError | null>(null);

  protected readonly savingEmail = signal(false);
  protected readonly emailError = signal<ApiError | null>(null);

  protected readonly savingPassword = signal(false);
  protected readonly passwordError = signal<ApiError | null>(null);

  protected readonly detailsForm = this.fb.nonNullable.group({
    firstName: ['', Validators.required],
    lastName: ['', Validators.required],
    titles: [''],
    guns: [''],
    optics: [''],
    ammo: [''],
    instagramHandle: [''],
    bio: [''],
  });

  protected readonly emailForm = this.fb.nonNullable.group({
    email: ['', [Validators.required, Validators.email]],
  });

  protected readonly passwordForm = this.fb.nonNullable.group({
    oldPassword: ['', Validators.required],
    newPassword: ['', [Validators.required, Validators.minLength(14)]],
  });

  ngOnInit(): void {
    const user = this.auth.currentUser();
    if (!user) {
      return;
    }
    this.detailsForm.setValue({
      firstName: user.firstName,
      lastName: user.lastName,
      titles: user.titles,
      guns: user.guns,
      optics: user.optics,
      ammo: user.ammo,
      instagramHandle: user.instagramHandle,
      bio: user.bio,
    });
    this.emailForm.setValue({ email: user.email });
  }

  saveDetails(): void {
    const userId = this.auth.currentUser()?.id;
    if (this.detailsForm.invalid || !userId) {
      return;
    }
    this.savingDetails.set(true);
    this.userProfileService.patch(userId, this.detailsForm.getRawValue()).subscribe({
      next: () => {
        this.auth.refreshSession().subscribe();
        this.savingDetails.set(false);
        this.snackBar.open('Profile updated', 'Dismiss', { duration: 3000 });
      },
      error: (err) => {
        this.detailsError.set(toApiError(err));
        this.savingDetails.set(false);
      },
    });
  }

  saveEmail(): void {
    const userId = this.auth.currentUser()?.id;
    if (this.emailForm.invalid || !userId) {
      return;
    }
    this.savingEmail.set(true);
    this.userProfileService.changeEmail(userId, this.emailForm.getRawValue()).subscribe({
      next: () => {
        this.auth.refreshSession().subscribe();
        this.savingEmail.set(false);
        this.snackBar.open('Email updated. Check your inbox to verify it.', 'Dismiss', {
          duration: 4000,
        });
      },
      error: (err) => {
        this.emailError.set(toApiError(err));
        this.savingEmail.set(false);
      },
    });
  }

  savePassword(): void {
    const userId = this.auth.currentUser()?.id;
    if (this.passwordForm.invalid || !userId) {
      return;
    }
    this.savingPassword.set(true);
    this.userProfileService.changePassword(userId, this.passwordForm.getRawValue()).subscribe({
      next: () => {
        this.savingPassword.set(false);
        this.passwordForm.reset({ oldPassword: '', newPassword: '' });
        this.snackBar.open('Password updated', 'Dismiss', { duration: 3000 });
      },
      error: (err) => {
        this.passwordError.set(toApiError(err));
        this.savingPassword.set(false);
      },
    });
  }
}
