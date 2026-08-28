import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map, shareReplay } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdHeaders } from '../../core/api/api-headers';
import { JsonLdCollection, unwrapCollection } from '../../core/api/json-ld';
import { MatchType } from '../../models/match-reference';

@Injectable({ providedIn: 'root' })
export class MatchReferenceService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  // Match types rarely change and are used to populate several dropdowns
  // (gathering form, registration form), so cache the one request.
  private matchTypes$?: Observable<MatchType[]>;

  listMatchTypes(): Observable<MatchType[]> {
    if (!this.matchTypes$) {
      this.matchTypes$ = this.http
        .get<JsonLdCollection<MatchType>>(`${this.base}/match_type`, { headers: jsonLdHeaders })
        .pipe(map(unwrapCollection), shareReplay(1));
    }
    return this.matchTypes$;
  }
}
