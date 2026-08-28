export interface Video {
  id: string;
  url: string;
  description: string;
}

export interface VideoWriteInput {
  url: string;
  description: string;
}
