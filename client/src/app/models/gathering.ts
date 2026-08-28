import { Iri } from '../core/api/json-ld';
import { Club } from './club';

export interface Gathering {
  id: string;
  address: string;
  state: string;
  city: string;
  country: string;
  description: string;
  openDate: string;
  closeDate: string;
  startDate: string;
  endDate: string;
  price: number;
  waiver: string;
  eventType: string;
  matchType: string;
  club: Iri | Club;
}

export interface GatheringWriteInput {
  address: string;
  state: string;
  city: string;
  country: string;
  description: string;
  openDate: string;
  closeDate: string;
  startDate: string;
  endDate: string;
  waiver: string;
  eventType: string;
  matchType: string;
}
