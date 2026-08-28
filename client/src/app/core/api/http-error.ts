import { HttpErrorResponse } from '@angular/common/http';

/** Normalized shape for API Platform's `application/problem+json` error bodies. */
export interface ApiError {
  status: number;
  title: string;
  detail?: string;
  violations?: { propertyPath: string; message: string }[];
}

export function toApiError(err: unknown): ApiError {
  if (err instanceof HttpErrorResponse) {
    const body = err.error as
      | { title?: string; detail?: string; 'hydra:description'?: string; violations?: { propertyPath: string; message: string }[] }
      | null
      | undefined;
    return {
      status: err.status,
      title: body?.title ?? defaultTitleFor(err.status),
      detail: body?.detail ?? body?.['hydra:description'] ?? err.message,
      violations: body?.violations,
    };
  }
  return { status: 0, title: 'Unexpected error', detail: String(err) };
}

function defaultTitleFor(status: number): string {
  switch (status) {
    case 401:
      return 'Not signed in';
    case 403:
      return 'Not allowed';
    case 404:
      return 'Not found';
    case 422:
      return 'Invalid data';
    default:
      return 'Something went wrong';
  }
}
