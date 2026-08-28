import { Iri } from '../core/api/json-ld';
import { Gathering } from './gathering';
import { User } from './user';

export interface Registration {
  id: string;
  division: string | null;
  classification: string | null;
  powerFactor: string | null;
  memberNumber: string;
  category: number;
  user: Iri | User;
  gathering: Iri | Gathering;
}

export interface RegistrationWriteInput {
  division: string | null;
  classification: string | null;
  powerFactor: string | null;
  memberNumber: string;
  category: number;
}
