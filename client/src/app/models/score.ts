import { Iri } from '../core/api/json-ld';
import { Gathering } from './gathering';
import { User } from './user';

export interface Score {
  id: string;
  alpha: number;
  charlie: number;
  delta: number;
  mike: number;
  noShoot: number;
  user: Iri | User;
  gathering: Iri | Gathering;
}

export interface ScoreWriteInput {
  alpha: number;
  charlie: number;
  delta: number;
  mike: number;
  noShoot: number;
}
