import { createAsyncThunk } from "@reduxjs/toolkit";
import { setUploadProgress, UploadState } from "./UploadSlice";
import { uploadProgress, uploadService } from "./uploadApi";
import { AxiosProgressEvent } from "axios";

export const updateVideo = createAsyncThunk(
    "uploads/uploadVideo",
    async ({ videoId, id, file, field }: UploadState, thunkApi) => {
        const onUploadProgress = (progressEvent: AxiosProgressEvent) => {
            const progress = uploadProgress(progressEvent);
            thunkApi.dispatch(setUploadProgress({ id, progress }));
        };

        try {
            const params = { videoId, id, file, field, onUploadProgress };
            const response = await uploadService(params);
            return response;
        } catch (error) {
            return thunkApi.rejectWithValue(error);
        }
    }
);
