import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdHeaders } from '../../core/api/api-headers';
import { UserPublicProfile } from '../../models/user';

@Injectable({ providedIn: 'root' })
export class ShooterService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  get(id: string): Observable<UserPublicProfile> {
    return this.http.get<UserPublicProfile>(`${this.base}/user/${id}/profile`, {
      headers: jsonLdHeaders,
    });
  }
}
