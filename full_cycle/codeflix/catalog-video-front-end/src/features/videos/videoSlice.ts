import { Results, VideoParams } from "../../types/Video";
import { apiSlice } from "../api/apiSlice";

const endpoint = "/videos";

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
    return `${endpoint}?${parseQueryParams(params)}`;
};

const videosSlice = apiSlice.injectEndpoints({
    endpoints: ({ query }) => ({
        getVideos: query<Results, VideoParams>({
            query: getVideos,
            providesTags: ["Videos"],
        }),
    }),
});

export const { useGetVideosQuery } = videosSlice;
