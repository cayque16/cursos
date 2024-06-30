import { Box, Paper, Typography } from "@mui/material";
import { useSnackbar } from "notistack";
import { useEffect, useState } from "react";
import { Video } from "../../types/Video";
import { VideoForm } from "./components/VideoForm";
import { mapVideoToForm } from "./utils";
import { initialState, useCreateVideoMutation, useGetAllCastMembersQuery, useGetAllGenresQuery } from "./videoSlice";
import { Category } from "../categories/categorySlice";

export const VideoCreate = () => {
    const { enqueueSnackbar } = useSnackbar();
    const { data: genres } = useGetAllGenresQuery();
    const { data: castMembers } = useGetAllCastMembersQuery();
    const [createVideo, status] = useCreateVideoMutation();
    const [videoState, setVideoState] = useState<Video>(initialState);
    const [categories, setCategories] = useState<(Category | undefined)[]>();

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setVideoState((state) => ({...state, [name]: value }));
    }

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        await createVideo(mapVideoToForm(videoState));
    }

    useEffect(() => {
        console.log(videoState.genres);
        const categories = videoState.genres
            ?.map(({ categories }) => categories)
            .flat();
            
        const uniqueCategories = categories?.filter((category, index, self) => {
            return self.findIndex((c) => c?.id === category?.id) === index;
        });
        
        setCategories(uniqueCategories);
    }, [videoState.genres]);

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
                    // @ts-ignore
                    categories={categories}
                    castMembers={castMembers?.data}
                />
            </Paper>
        </Box>
    );
};
