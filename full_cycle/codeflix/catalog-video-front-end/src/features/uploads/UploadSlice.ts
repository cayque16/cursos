import { createSlice, PayloadAction } from "@reduxjs/toolkit";

export interface UploadState {
    id: string;
    file: File;
    field: string;
    videoId: string;
    progress: number;
    status?: "idle" | "loading" | "success" | "failed";
}

const initialState: UploadState[] = [];

const uploadsSlice = createSlice({
    name: "uploads",
    initialState,
    reducers: {
        addUpload(state, action: PayloadAction<UploadState>) {
            state.push({ ...action.payload, status: "idle", progress: 0 });
        },
    },
});

export const { addUpload } = uploadsSlice.actions;

export const uploadReducer = uploadsSlice.reducer;
