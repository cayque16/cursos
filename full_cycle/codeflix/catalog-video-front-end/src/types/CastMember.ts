export interface Results {
    data: CastMember[];
    links: Links;
    meta: Meta;
}

export interface CastMember {
    id: string;
    name: string;
    type: number;
    deleteAt: null;
    createdAt: string;
    updatedAt: string;
}

export interface Links {
    prev: null;
    last: string;
    next: string;
    first: string;
}

export interface Meta {
    to: number;
    from: number;
    path: string;
    total: number;
    perPage: number;
    lastPage: number;
    currentPage: number;
}

export interface CastMemberParams {
    page?: number;
    type?: number;
    search?: string;
    perPage?: number;
}
