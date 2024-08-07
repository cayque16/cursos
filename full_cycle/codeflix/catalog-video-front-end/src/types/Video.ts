import { CastMember } from "./CastMember";
import { Category } from "./Category";
import { Genre } from "./Genre";

export type FileObject = {
  name: string;
  file: File;
}

export interface Results {
  data: Video[];
  links: Links;
  meta: Meta;
}

export interface Result {
  data: Video;
  links: Links;
  meta: Meta;
}

export interface Video {
  id: string;
  title: string;
  description: string;
  year_launched: number;
  opened: boolean;
  rating: string;
  duration: number;
  deleted_at?: string;
  created_at: string;
  updated_at: string;
  genres?: Genre[];
  categories?: Category[];
  cast_members?: CastMember[];
  video_file?: string;
  trailer_file?: string;
  banner_file?: string;
  thumb_file?: string;
  thumb_half_file?: string;
}

export interface Links {
  first: string;
  last: string;
  prev: string;
  next: string;
}

export interface Meta {
  to?: number;
  from?: number;
  path?: string;
  total?: number;
  per_page?: number;
  last_page?: number;
  current_page?: number;
}

export interface VideoParams {
  page?: number;
  perPage?: number;
  filter?: string;
  isActive?: boolean;
}

export interface VideoPayload {
  id: string;
  title: string;
  rating: string;
  opened: boolean;
  duration: number;
  description: string;
  genres?: string[];
  year_launched: number;
  categories?: string[];
  cast_members?: string[];
}
