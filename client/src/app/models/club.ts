export interface Club {
  id: string;
  name: string;
  email: string;
  address: string;
  socials: string;
  website: string;
}

export interface ClubPatchInput {
  name: string;
  email: string;
  address: string;
  socials: string;
  website: string;
}
