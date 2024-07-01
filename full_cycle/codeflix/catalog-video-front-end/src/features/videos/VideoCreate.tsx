import { Box, Paper, Typography } from "@mui/material";
import { useSnackbar } from "notistack";
import { useEffect, useRef, useState } from "react";
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
    const [uniqueCategories, setUniqueCategories] = useState<Category[]>([]);
    const categoriesToKeepRef = useRef<Category[] | undefined>(undefined);

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setVideoState((state) => ({...state, [name]: value }));
    }

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        await createVideo(mapVideoToForm(videoState));
    }

    const filterById = (
        category: Category | undefined,
        index: number,
        self: (Category | undefined)[]
    ): boolean => index === self.findIndex((c) => c?.id === category?.id);

    useEffect(() => {
        const uniqueCategories = videoState.genres
            ?.flatMap(({ categories }) => categories)
            .filter(filterById) as Category[];

        setUniqueCategories(uniqueCategories);
    }, [videoState.genres]);

    useEffect(() => {
        categoriesToKeepRef.current = videoState.categories?.filter((category) => 
            uniqueCategories.find((c) => c?.id === category.id)
        );
    }, [uniqueCategories, videoState.categories]);

    useEffect(() => {
        // @ts-ignore
        setVideoState((state: Video) => ({...state, categories: categoriesToKeepRef.current }));
    }, [uniqueCategories, setVideoState]);

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
                    categories={uniqueCategories}
                    castMembers={castMembers?.data}
                />
            </Paper>
        </Box>
    );
};
