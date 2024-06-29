import { Box, Paper, Typography } from "@mui/material";
import { VideoForm } from "./components/VideoForm";
import { useSnackbar } from "notistack";
import { initialState, useCreateVideoMutation, useGetAllCastMembersQuery, useGetAllCategoriesQuery, useGetAllGenresQuery } from "./videoSlice";
import { Video } from "../../types/Video";
import { useEffect, useState } from "react";
import { mapVideoToForm } from "./utils";

export const VideoCreate = () => {
    const { enqueueSnackbar } = useSnackbar();
    const { data: genres } = useGetAllGenresQuery();
    const { data: categories } = useGetAllCategoriesQuery();
    const { data: castMembers } = useGetAllCastMembersQuery();
    const [createVideo, status] = useCreateVideoMutation();
    const [videoState, setVideoState] = useState<Video>(initialState);

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setVideoState((state) => ({...state, [name]: value }));
    }

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        await createVideo(mapVideoToForm(videoState));
    }

    useEffect(() => {
        if (status.isSuccess) {
            enqueueSnackbar("Video created successfully", { variant: "success" });
        }
        if (status.isError) {
            enqueueSnackbar("Video not created", { variant: "error" });
        }
    }, [status, enqueueSnackbar]);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Create Video</Typography>
                    </Box>
                </Box>

                <VideoForm
                    video={videoState}
                    genres={genres?.data}
                    handleChange={handleChange}
                    handleSubmit={handleSubmit}
                    isLoading={status.isLoading}
                    isDisabled={status.isLoading}
                    categories={categories?.data}
                    castMembers={castMembers?.data}
                />
            </Paper>
        </Box>
    );
};
