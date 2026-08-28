import { HttpHeaders } from '@angular/common/http';

/** The API only registers the JSON-LD format, so these are the exact headers it accepts. */
export const jsonLdHeaders = new HttpHeaders({ Accept: 'application/ld+json' });

export const jsonLdWriteHeaders = new HttpHeaders({
  Accept: 'application/ld+json',
  'Content-Type': 'application/ld+json',
});

export const mergePatchHeaders = new HttpHeaders({
  Accept: 'application/ld+json',
  'Content-Type': 'application/merge-patch+json',
});
