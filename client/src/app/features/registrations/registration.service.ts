import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdHeaders, jsonLdWriteHeaders, mergePatchHeaders } from '../../core/api/api-headers';
import { JsonLdCollection, unwrapCollection } from '../../core/api/json-ld';
import { Registration, RegistrationWriteInput } from '../../models/registration';

@Injectable({ providedIn: 'root' })
export class RegistrationService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  listForGathering(gatheringId: string): Observable<Registration[]> {
    return this.http
      .get<JsonLdCollection<Registration>>(`${this.base}/gathering/${gatheringId}/registration`, {
        headers: jsonLdHeaders,
      })
      .pipe(map(unwrapCollection));
  }

  listForUser(userId: string): Observable<Registration[]> {
    return this.http
      .get<JsonLdCollection<Registration>>(`${this.base}/user/${userId}/registration`, {
        headers: jsonLdHeaders,
      })
      .pipe(map(unwrapCollection));
  }

  create(gatheringId: string, input: RegistrationWriteInput): Observable<Registration> {
    return this.http.post<Registration>(
      `${this.base}/gathering/${gatheringId}/registration`,
      input,
      { headers: jsonLdWriteHeaders },
    );
  }

  patch(userId: string, id: string, input: Partial<RegistrationWriteInput>): Observable<Registration> {
    return this.http.patch<Registration>(
      `${this.base}/user/${userId}/registration/${id}`,
      input,
      { headers: mergePatchHeaders },
    );
  }

  cancel(userId: string, id: string): Observable<void> {
    return this.http.delete<void>(`${this.base}/user/${userId}/registration/${id}`);
  }
}
