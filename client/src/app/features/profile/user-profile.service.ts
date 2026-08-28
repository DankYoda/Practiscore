import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdWriteHeaders, mergePatchHeaders } from '../../core/api/api-headers';
import { EmailChangeInput, PasswordChangeInput, User, UserPatchInput } from '../../models/user';

@Injectable({ providedIn: 'root' })
export class UserProfileService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  patch(userId: string, input: UserPatchInput): Observable<User> {
    return this.http.patch<User>(`${this.base}/user/${userId}`, input, { headers: mergePatchHeaders });
  }

  changeEmail(userId: string, input: EmailChangeInput): Observable<void> {
    return this.http.put<void>(`${this.base}/user/${userId}/email/change`, input, {
      headers: jsonLdWriteHeaders,
    });
  }

  changePassword(userId: string, input: PasswordChangeInput): Observable<void> {
    return this.http.put<void>(`${this.base}/user/${userId}/password/change`, input, {
      headers: jsonLdWriteHeaders,
    });
  }
}
