import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdHeaders, mergePatchHeaders } from '../../core/api/api-headers';
import { JsonLdCollection, unwrapCollection } from '../../core/api/json-ld';
import { Club, ClubPatchInput } from '../../models/club';

@Injectable({ providedIn: 'root' })
export class ClubService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  list(): Observable<Club[]> {
    return this.http
      .get<JsonLdCollection<Club>>(`${this.base}/club`, { headers: jsonLdHeaders })
      .pipe(map(unwrapCollection));
  }

  get(id: string): Observable<Club> {
    return this.http.get<Club>(`${this.base}/club/${id}`, { headers: jsonLdHeaders });
  }

  patch(id: string, input: Partial<ClubPatchInput>): Observable<Club> {
    return this.http.patch<Club>(`${this.base}/club/${id}`, input, { headers: mergePatchHeaders });
  }
}
