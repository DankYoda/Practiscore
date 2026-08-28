/** An API Platform resource identifier, e.g. "/club/b81791f6-...". */
export type Iri = string;

/** Pulls the trailing id segment off an IRI, e.g. "/club/{id}" -> "{id}". */
export function iriToId(iri: Iri): string {
  const segments = iri.split('/').filter(Boolean);
  return segments[segments.length - 1] ?? iri;
}

/**
 * API Platform's JSON-LD collection envelope. Recent API Platform versions use
 * plain `member`/`totalItems`; older Hydra-flavored responses use the
 * `hydra:`-prefixed keys. Both are optional here so `unwrapCollection` can
 * tolerate either shape without guessing wrong.
 */
export interface JsonLdCollection<T> {
  'member'?: T[];
  'hydra:member'?: T[];
  'totalItems'?: number;
  'hydra:totalItems'?: number;
}

export function unwrapCollection<T>(body: JsonLdCollection<T> | T[]): T[] {
  if (Array.isArray(body)) {
    return body;
  }
  return body['member'] ?? body['hydra:member'] ?? [];
}

export function unwrapTotalItems<T>(body: JsonLdCollection<T> | T[]): number | undefined {
  if (Array.isArray(body)) {
    return body.length;
  }
  return body['totalItems'] ?? body['hydra:totalItems'];
}

/**
 * API Platform relations serialize as a bare IRI string unless the operation
 * explicitly embeds the related resource. Models type these fields as
 * `Iri | T`, so components read the id through this helper instead of
 * assuming one shape.
 */
export function relationId(value: Iri | { id: string } | null | undefined): string | null {
  if (value == null) {
    return null;
  }
  return typeof value === 'string' ? iriToId(value) : value.id;
}
