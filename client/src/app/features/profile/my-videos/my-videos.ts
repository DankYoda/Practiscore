import { Component, OnInit, inject, signal } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatIconModule } from '@angular/material/icon';
import { AuthService } from '../../../core/auth/auth.service';
import { ApiError, toApiError } from '../../../core/api/http-error';
import { ErrorMessage } from '../../../core/ui/error-message/error-message';
import { LoadingSpinner } from '../../../core/ui/loading-spinner/loading-spinner';
import { Video } from '../../../models/video';
import { VideoService } from '../video.service';

@Component({
  selector: 'app-my-videos',
  imports: [
    ReactiveFormsModule,
    MatCardModule,
    MatButtonModule,
    MatFormFieldModule,
    MatInputModule,
    MatIconModule,
    ErrorMessage,
    LoadingSpinner,
  ],
  templateUrl: './my-videos.html',
  styleUrl: './my-videos.css',
})
export class MyVideos implements OnInit {
  private readonly auth = inject(AuthService);
  private readonly videoService = inject(VideoService);
  private readonly fb = inject(FormBuilder);

  protected readonly videos = signal<Video[]>([]);
  protected readonly loading = signal(true);
  protected readonly saving = signal(false);
  protected readonly error = signal<ApiError | null>(null);

  protected readonly form = this.fb.nonNullable.group({
    url: ['', Validators.required],
    description: ['', Validators.required],
  });

  ngOnInit(): void {
    const userId = this.auth.currentUser()?.id;
    if (!userId) {
      this.loading.set(false);
      return;
    }
    this.videoService.list(userId).subscribe({
      next: (videos) => {
        this.videos.set(videos);
        this.loading.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.loading.set(false);
      },
    });
  }

  add(): void {
    const userId = this.auth.currentUser()?.id;
    if (this.form.invalid || !userId) {
      return;
    }
    this.saving.set(true);
    this.videoService.create(userId, this.form.getRawValue()).subscribe({
      next: (video) => {
        this.videos.update((list) => [...list, video]);
        this.form.reset({ url: '', description: '' });
        this.saving.set(false);
      },
      error: (err) => {
        this.error.set(toApiError(err));
        this.saving.set(false);
      },
    });
  }

  remove(video: Video): void {
    const userId = this.auth.currentUser()?.id;
    if (!userId) {
      return;
    }
    this.videoService.remove(userId, video.id).subscribe({
      next: () => this.videos.update((list) => list.filter((v) => v.id !== video.id)),
      error: (err) => this.error.set(toApiError(err)),
    });
  }
}
