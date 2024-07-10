import { Result, Results, VideoParams, VideoPayload } from "../../types/Video";
import { apiSlice } from "../api/apiSlice";
import { Results as CategoriesResults } from "../../types/Category";
import { Results as CastMembersResults } from "../../types/CastMember";
import { Genres } from "../../types/Genre";

const endpointUrl = "/videos";

export const initialState = {
    id: "",
    title: "",
    description: "",
    year_launched: 0,
    opened: false,
    rating: "",
    duration: 0,
    deleted_at: "",
    created_at: "",
    updated_at: "",
    genres: [],
    categories: [],
    cast_members: [],
    video_file: "",
    trailer_file: "",
    banner_file: "",
    thumb_file: "",
    thumb_half_file: "",
};

function parseQueryParams(params: VideoParams) {
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
    if (params.isActive) {
        query.append("is_active", params.isActive.toString());
    }
    return query.toString();
}

const getVideos = ({ page = 1, perPage = 10, filter = "" }) => {
    const params: VideoParams = { page, perPage, filter };
    return `${endpointUrl}?${parseQueryParams(params)}`;
};

function  deleteVideo({ id }: { id: string }) {
    return { url: `${endpointUrl}/${id}`, method: "DELETE" };
}

function getVideo({ id }: { id: string }) {
    return `${endpointUrl}/${id}`;
}

function updateVideo(video: VideoPayload) {
    return {
        url: `${endpointUrl}/${video.id}`,
        method: "PUT",
        body: video,
    };
}

function createVideo(video: VideoPayload) {
    return {
        url: endpointUrl,
        method: "POST",
        body: video,
    };
}

function getAllCategories() {
    return `${endpointUrl}/categories?all=true`;
}

function getAllGenres() {
    return `genres?all=true`;
}

function getAllCastMembers() {
    return `cast_members?all=true`;
}

export const videoSlice = apiSlice.injectEndpoints({
    endpoints: ({ query, mutation }) => ({
        createVideo: mutation<Result, VideoPayload>({
            query: createVideo,
            invalidatesTags: ["Videos"],
        }),
        getVideos: query<Results, VideoParams>({
            query: getVideos,
            providesTags: ["Videos"],
        }),
        getVideo: query<Result, { id: string }>({
            query: getVideo,
            providesTags: ["Videos"],
        }),
        getAllCategories: query<CategoriesResults, void>({
            query: getAllCategories,
            providesTags: ["Categories"],
        }),
        getAllGenres: query<Genres, void>({
            query: getAllGenres,
            providesTags: ["Genres"],
        }),
        getAllCastMembers: query<CastMembersResults, void>({
            query: getAllCastMembers,
            providesTags: ["CastMembers"],
        }),
        deleteVideo: mutation<Result, { id: string }>({
            query: deleteVideo,
            invalidatesTags: ["Videos"],
        }),
        updateVideo: mutation<Result, VideoPayload>({
            query: updateVideo,
            invalidatesTags: ["Videos"],
        }),
    }),
});

export const { 
    useGetVideoQuery,
    useGetVideosQuery,
    useGetAllGenresQuery,
    useCreateVideoMutation,
    useDeleteVideoMutation,
    useUpdateVideoMutation,
    useGetAllCategoriesQuery,
    useGetAllCastMembersQuery,
 } = videoSlice;
