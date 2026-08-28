import { Iri } from '../core/api/json-ld';

export interface Division {
  name: string;
  matchType: Iri | MatchType;
}

export interface Classification {
  name: string;
  ordinal: number;
  matchType: Iri | MatchType;
}

export interface MatchType {
  name: string;
  category: number;
  divisions: Division[];
  classifications: Classification[];
}
