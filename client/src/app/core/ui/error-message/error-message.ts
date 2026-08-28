import { Component, input } from '@angular/core';
import { MatIconModule } from '@angular/material/icon';
import { ApiError } from '../../api/http-error';

@Component({
  selector: 'app-error-message',
  imports: [MatIconModule],
  template: `
    <div class="error-message">
      <mat-icon>error_outline</mat-icon>
      <div>
        <p class="error-message__title">{{ error().title }}</p>
        @if (error().detail) {
          <p class="error-message__detail">{{ error().detail }}</p>
        }
      </div>
    </div>
  `,
  styles: `
    .error-message {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      padding: 16px;
      border-radius: 8px;
      background: var(--mat-sys-error-container, #fce8e6);
      color: var(--mat-sys-on-error-container, #601410);
    }

    .error-message__title {
      font-weight: 500;
      margin: 0;
    }

    .error-message__detail {
      margin: 4px 0 0;
      opacity: 0.85;
    }
  `,
})
export class ErrorMessage {
  readonly error = input.required<ApiError>();
}
