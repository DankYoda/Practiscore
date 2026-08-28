import { HttpClient } from '@angular/common/http';
import { Injectable, computed, inject, signal } from '@angular/core';
import { Observable, catchError, of, switchMap, tap } from 'rxjs';
import { environment } from '../../../environments/environment';
import { User, UserRegistrationInput } from '../../models/user';
import { jsonLdHeaders, jsonLdWriteHeaders } from '../api/api-headers';
import { relationId } from '../api/json-ld';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  private readonly currentUserSignal = signal<User | null>(null);
  private readonly initializedSignal = signal(false);

  readonly currentUser = this.currentUserSignal.asReadonly();
  readonly initialized = this.initializedSignal.asReadonly();
  readonly isAuthenticated = computed(() => this.currentUserSignal() !== null);
  readonly isAdmin = computed(() => this.currentUserSignal()?.roles.includes('ROLE_ADMIN') ?? false);
  readonly managedClubId = computed(() => relationId(this.currentUserSignal()?.managedClub ?? null));

  managesClub(clubId: string | null | undefined): boolean {
    return !!clubId && this.managedClubId() === clubId;
  }

  login(username: string, password: string): Observable<User | null> {
    return this.http
      .post(`${this.base}/login`, { username, password })
      .pipe(switchMap(() => this.refreshSession()));
  }

  register(payload: UserRegistrationInput): Observable<User> {
    return this.http.post<User>(`${this.base}/user/register`, payload, { headers: jsonLdWriteHeaders });
  }

  logout(): Observable<void> {
    return this.http
      .post<void>(`${this.base}/logout`, {})
      .pipe(tap(() => this.currentUserSignal.set(null)));
  }

  /** Fetches the current session from the httpOnly cookie. Called on app init and after login. */
  refreshSession(): Observable<User | null> {
    return this.http.get<User>(`${this.base}/user/me`, { headers: jsonLdHeaders }).pipe(
      tap((user) => this.currentUserSignal.set(user)),
      catchError(() => {
        this.currentUserSignal.set(null);
        return of(null);
      }),
      tap(() => this.initializedSignal.set(true)),
    );
  }
}
