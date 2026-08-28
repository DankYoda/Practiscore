import { Iri } from '../core/api/json-ld';
import { Club } from './club';

/** Full shape returned by /user/me and /user/{id} (self-or-admin only). */
export interface User {
  id: string;
  firstName: string;
  lastName: string;
  username: string;
  email: string;
  emailVerified: boolean;
  titles: string;
  guns: string;
  optics: string;
  ammo: string;
  instagramHandle: string;
  bio: string;
  homeClub: Iri | Club | null;
  managedClub: Iri | Club | null;
  roles: string[];
}

/** Public shape returned by /user/{id}/profile — no email/admin/relations. */
export interface UserPublicProfile {
  id: string;
  firstName: string;
  lastName: string;
  username: string;
  titles: string;
  guns: string;
  optics: string;
  ammo: string;
  instagramHandle: string;
  bio: string;
  homeClub: Iri | Club | null;
}

/** Fields the self-or-admin Patch actually accepts (user:patch:write group). */
export interface UserPatchInput {
  firstName?: string;
  lastName?: string;
  titles?: string;
  guns?: string;
  optics?: string;
  ammo?: string;
  instagramHandle?: string;
  bio?: string;
}

export interface UserRegistrationDetails {
  username: string;
  firstName: string;
  lastName: string;
  email: string;
  titles: string;
  guns: string;
  optics: string;
  ammo: string;
  instagramHandle: string;
  bio: string;
}

export interface UserRegistrationInput {
  user: UserRegistrationDetails;
  plainPassword: string;
}

export interface EmailChangeInput {
  email: string;
}

export interface PasswordChangeInput {
  oldPassword: string;
  newPassword: string;
}

export interface PasswordResetInput {
  newPassword: string;
}
