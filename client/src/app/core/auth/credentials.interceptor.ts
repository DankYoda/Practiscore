import { HttpInterceptorFn } from '@angular/common/http';
import { environment } from '../../../environments/environment';

/** The API auth session lives in an httpOnly cookie, so every API request needs credentials. */
export const credentialsInterceptor: HttpInterceptorFn = (req, next) => {
  if (!req.url.startsWith(environment.apiUrl)) {
    return next(req);
  }
  return next(req.clone({ withCredentials: true }));
};
