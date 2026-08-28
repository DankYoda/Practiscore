import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdHeaders, jsonLdWriteHeaders, mergePatchHeaders } from '../../core/api/api-headers';
import { JsonLdCollection, unwrapCollection } from '../../core/api/json-ld';
import { Score, ScoreWriteInput } from '../../models/score';

@Injectable({ providedIn: 'root' })
export class ScoreService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  listForGathering(gatheringId: string): Observable<Score[]> {
    return this.http
      .get<JsonLdCollection<Score>>(`${this.base}/gathering/${gatheringId}/score`, {
        headers: jsonLdHeaders,
      })
      .pipe(map(unwrapCollection));
  }

  listForUserAtGathering(gatheringId: string, userId: string): Observable<Score[]> {
    return this.http
      .get<JsonLdCollection<Score>>(
        `${this.base}/gathering/${gatheringId}/user/${userId}/score`,
        { headers: jsonLdHeaders },
      )
      .pipe(map(unwrapCollection));
  }

  create(gatheringId: string, userId: string, input: ScoreWriteInput): Observable<Score> {
    return this.http.post<Score>(
      `${this.base}/gathering/${gatheringId}/user/${userId}/score`,
      input,
      { headers: jsonLdWriteHeaders },
    );
  }

  patch(
    gatheringId: string,
    userId: string,
    id: string,
    input: Partial<ScoreWriteInput>,
  ): Observable<Score> {
    return this.http.patch<Score>(
      `${this.base}/gathering/${gatheringId}/user/${userId}/score/${id}`,
      input,
      { headers: mergePatchHeaders },
    );
  }
}
