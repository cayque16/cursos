import { Box, Paper, Typography } from "@mui/material";
import { useSnackbar } from "notistack";
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { Video } from "../../types/Video";
import { VideoForm } from "./components/VideoForm";
import { initialState, useGetAllCastMembersQuery, useGetAllCategoriesQuery, useGetAllGenresQuery, useGetVideoQuery, useUpdateVideoMutation } from "./videoSlice";
import { mapVideoToForm } from "./utils";

export function VideoEdit() {
    const id = useParams<{ id: string }>().id as string;
    const { enqueueSnackbar } = useSnackbar();
    const { data: video, isFetching } = useGetVideoQuery({ id });
    const [videoState, setVideoState] = useState<Video>(initialState);
    const [updateVideo, status] = useUpdateVideoMutation();
    const { data: categories } = useGetAllCategoriesQuery();
    const { data: genres } = useGetAllGenresQuery();
    const { data: castMembers } = useGetAllCastMembersQuery();

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setVideoState((state) => ({...state, [name]: value }));
    }
    
    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        await updateVideo(mapVideoToForm(videoState));
    }

    useEffect(() => {
        if (video) {
            setVideoState(video.data);
        }
    }, [video]);

    useEffect(() => {
        if (status.isSuccess) {
            enqueueSnackbar("Video updated", { variant: "success" });
        }
        if (status.isError) {
            enqueueSnackbar("Video not updated", { variant: "error" });
        }
    }, [status, enqueueSnackbar]);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Edit Video</Typography>
                    </Box>
                </Box>
                <VideoForm
                    video={videoState}
                    handleSubmit={handleSubmit}
                    handleChange={handleChange}
                    genres={genres?.data}
                    categories={categories?.data}
                    castMembers={castMembers?.data}
                    isDisabled={isFetching}
                    isLoading={isFetching}
                />
            </Paper>
        </Box>
    );
}
