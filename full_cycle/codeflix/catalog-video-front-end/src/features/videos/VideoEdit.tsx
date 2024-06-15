import { Box, Paper, Typography } from "@mui/material";
import { useParams } from "react-router-dom";
import { initialState, useGetVideoQuery, useUpdateVideoMutation } from "./videoSlice";
import { useEffect, useState } from "react";
import { Video } from "../../types/Video";
import { useSnackbar } from "notistack";
import { VideoForm } from "./components/VideoForm";

export function VideoEdit() {
    const id = useParams<{ id: string }>().id as string;
    const { enqueueSnackbar } = useSnackbar();
    const { data: video, isFetching } = useGetVideoQuery({ id });
    const [videoState, setVideoState] = useState<Video>(initialState);
    const [updateVideo, status] = useUpdateVideoMutation();

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setVideoState((state) => ({...state, [name]: value }));
    }

    async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        await updateVideo(videoState);
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
                    genres={[]}
                    categories={[]}
                    castMembers={[]}
                    isDisabled={isFetching}
                    isLoading={isFetching}
                />
            </Paper>
        </Box>
    );
}
