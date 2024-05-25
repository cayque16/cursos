import { CastMember, CastMemberParams, Result, Results } from "../../types/CastMember";
import { apiSlice } from "../api/apiSlice";

const endpointUrl = "/cast_members";

export const initialState: CastMember = {
    id: "",
    name: "",
    type: 0,
    deleted_at: null,
    created_at: "",
    updated_at: "",
};

function parseQueryParams(params: CastMemberParams) {
    const query = new URLSearchParams();

    if (params.page) {
        query.append("page", params.page.toString());
    }
    if (params.perPage) {
        query.append("per_page", params.perPage.toString());
    }
    if (params.filter) {
        query.append("filter", params.filter);
    }
    if (params.type) {
        query.append("type", params.type.toString());
    }

    return query.toString();
}

function getCastMembers(params: CastMemberParams) {
    const { page = 1, perPage = 10, filter, type } = params;

    return `${endpointUrl}?${parseQueryParams({
        page,
        perPage,
        filter,
        type,
    })}`;
}

function deleteCastMember({ id }: { id: string }) {
    return {
        url: `${endpointUrl}/${id}`,
        method: "DELETE",
    };
}

function getCastMember({ id }: { id: string }) {
    return {
        method: "GET",
        url: `${endpointUrl}/${id}`,
    };
}

function updateCastMember(castMember: CastMember) {
    return {
        method: "PUT",
        body: castMember,
        url: `${endpointUrl}/${castMember.id}`,
    };
}

function createCastMember(castMember: CastMember) {
    return {
        method: "POST",
        url: endpointUrl,
        body: castMember,
    }
}

export const castMembersApiSlice = apiSlice.injectEndpoints({
    endpoints: ({ query, mutation }) => ({
        getCastMembers: query<Results, CastMemberParams>({
            query: getCastMembers,
            providesTags: ["CastMembers"],
        }),
        getCastMember: query<Result, { id: string }>({
            query: getCastMember,
            providesTags: ["CastMembers"],
        }),
        updateCastMember: mutation<Result, CastMember>({
            query: updateCastMember,
            invalidatesTags: ["CastMembers"],
        }),
        createCastMember: mutation<Result, CastMember>({
            query: createCastMember,
            invalidatesTags: ["CastMembers"],
        }),
        deleteCastMember: mutation<Result, { id: string }>({
            query: deleteCastMember,
            invalidatesTags: ["CastMembers"],
        }),
    }),
});

export const {
    useGetCastMemberQuery,
    useGetCastMembersQuery,
    useDeleteCastMemberMutation,
    useUpdateCastMemberMutation,
    useCreateCastMemberMutation,
} = castMembersApiSlice;
