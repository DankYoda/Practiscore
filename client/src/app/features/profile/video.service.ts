import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { jsonLdHeaders, jsonLdWriteHeaders, mergePatchHeaders } from '../../core/api/api-headers';
import { JsonLdCollection, unwrapCollection } from '../../core/api/json-ld';
import { Video, VideoWriteInput } from '../../models/video';

@Injectable({ providedIn: 'root' })
export class VideoService {
  private readonly http = inject(HttpClient);
  private readonly base = environment.apiUrl;

  list(userId: string): Observable<Video[]> {
    return this.http
      .get<JsonLdCollection<Video>>(`${this.base}/user/${userId}/video`, { headers: jsonLdHeaders })
      .pipe(map(unwrapCollection));
  }

  create(userId: string, input: VideoWriteInput): Observable<Video> {
    return this.http.post<Video>(`${this.base}/user/${userId}/video`, input, {
      headers: jsonLdWriteHeaders,
    });
  }

  patch(userId: string, id: string, input: Partial<VideoWriteInput>): Observable<Video> {
    return this.http.patch<Video>(`${this.base}/user/${userId}/video/${id}`, input, {
      headers: mergePatchHeaders,
    });
  }

  remove(userId: string, id: string): Observable<void> {
    return this.http.delete<void>(`${this.base}/user/${userId}/video/${id}`);
  }
}
