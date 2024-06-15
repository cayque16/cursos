import { Result, Results, VideoParams } from "../../types/Video";
import { apiSlice } from "../api/apiSlice";

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

export const videoSlice = apiSlice.injectEndpoints({
    endpoints: ({ query, mutation }) => ({
        getVideos: query<Results, VideoParams>({
            query: getVideos,
            providesTags: ["Videos"],
        }),
        getVideo: query<Result, { id: string }>({
            query: getVideo,
            providesTags: ["Videos"],
        }),
        deleteVideo: mutation<Result, { id: string }>({
            query: deleteVideo,
            invalidatesTags: ["Videos"],
        }),
    }),
});

export const { 
    useGetVideoQuery,
    useGetVideosQuery,
    useDeleteVideoMutation,
 } = videoSlice;
