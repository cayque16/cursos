import { CastMember, CastMemberParams, Result, Results } from "../../types/CastMember";
import { apiSlice } from "../api/apiSlice";

const endpointUrl = "/cast_members";

export const initialState: CastMember = {
    id: "",
    name: "",
    type: 0,
    deleteAt: null,
    createdAt: "",
    updatedAt: "",
};

function parseQueryParams(params: CastMemberParams) {
    const query = new URLSearchParams();

    if (params.page) {
        query.append("page", params.page.toString());
    }
    if (params.perPage) {
        query.append("per_page", params.perPage.toString());
    }
    if (params.search) {
        query.append("search", params.search);
    }
    if (params.type) {
        query.append("type", params.type.toString());
    }

    return query.toString();
}

function getCastMembers(params: CastMemberParams) {
    const { page = 1, perPage = 10, search, type } = params;

    return `${endpointUrl}?${parseQueryParams({
        page,
        perPage,
        search,
        type,
    })}`;
}

function deleteCastMember({ id }: { id: string }) {
    return {
        url: `${endpointUrl}/${id}`,
        method: "DELETE",
    };
}

export const castMembersApiSlice = apiSlice.injectEndpoints({
    endpoints: ({ query, mutation }) => ({
        getCastMembers: query<Results, CastMemberParams>({
            query: getCastMembers,
            providesTags: ["CastMembers"],
        }),
        deleteCastMember: mutation<Result, { id: string }>({
            query: deleteCastMember,
            invalidatesTags: ["CastMembers"],
        }),
    }),
});

export const {
    useGetCastMembersQuery,
    useDeleteCastMemberMutation,
} = castMembersApiSlice;
