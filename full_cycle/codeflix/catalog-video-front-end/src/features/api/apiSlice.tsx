import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";

const baseUrl = "http://192.168.2.10:8080/api";

export const apiSlice = createApi({
    reducerPath: "api",
    tagTypes: ["Categories", "CastMembers"],
    endpoints: (builder) => ({}),
    baseQuery: fetchBaseQuery({ baseUrl }),
});
