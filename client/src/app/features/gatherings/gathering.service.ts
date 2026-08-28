import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdHeaders, jsonLdWriteHeaders, mergePatchHeaders } from '../../core/api/api-headers';
import { JsonLdCollection, unwrapCollection } from '../../core/api/json-ld';
import { Gathering, GatheringWriteInput } from '../../models/gathering';
import { relationId } from '../../core/api/json-ld';

@Injectable({ providedIn: 'root' })
export class GatheringService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  // The API doesn't expose server-side filtering on GetCollection, so callers
  // that need "gatherings for a club" filter this list client-side.
  list(): Observable<Gathering[]> {
    return this.http
      .get<JsonLdCollection<Gathering>>(`${this.base}/gathering`, { headers: jsonLdHeaders })
      .pipe(map(unwrapCollection));
  }

  get(clubId: string, id: string): Observable<Gathering> {
    return this.http.get<Gathering>(`${this.base}/club/${clubId}/gathering/${id}`, {
      headers: jsonLdHeaders,
    });
  }

  /** There's no direct "/gathering/{id}" route (it's nested under its club), so routes that
   *  only carry the gathering id resolve it by scanning the collection. */
  getById(id: string): Observable<Gathering | undefined> {
    return this.list().pipe(map((gatherings) => gatherings.find((g) => g.id === id)));
  }

  clubIdOf(gathering: Gathering): string | null {
    return relationId(gathering.club);
  }

  create(clubId: string, input: GatheringWriteInput): Observable<Gathering> {
    return this.http.post<Gathering>(`${this.base}/club/${clubId}/gathering`, input, {
      headers: jsonLdWriteHeaders,
    });
  }

  patch(clubId: string, id: string, input: Partial<GatheringWriteInput>): Observable<Gathering> {
    return this.http.patch<Gathering>(`${this.base}/club/${clubId}/gathering/${id}`, input, {
      headers: mergePatchHeaders,
    });
  }
}
