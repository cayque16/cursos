import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";

export const baseUrl = "http://192.168.2.10:8080/api";

export const apiSlice = createApi({
    reducerPath: "api",
    tagTypes: ["Categories", "CastMembers", "Genres", "Videos"],
    endpoints: (builder) => ({}),
    baseQuery: fetchBaseQuery({ baseUrl }),
});
