import { Box, Paper, Typography } from "@mui/material";
import { useParams } from "react-router-dom";
import { initialState, useGetVideoQuery } from "./videoSlice";
import { useEffect, useState } from "react";
import { Video } from "../../types/Video";

export function VideoEdit() {
    const id = useParams<{ id: string }>().id as string;
    const { data: video, isFetching } = useGetVideoQuery({ id });
    const [videoState, setVideoState] = useState<Video>(initialState);

    useEffect(() => {
        if (video) {
            setVideoState(video.data);
        }
    }, [video]);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Edit Video</Typography>
                    </Box>
                </Box>
                {/* <GenreForm
                    genre={genreState}
                    handleSubmit={handleSubmit}
                    handleChange={handleChange}
                    categories={categories?.data}
                    isDisabled={status.isLoading}
                    isLoading={status.isLoading || isFetching}
                /> */}
            </Paper>
        </Box>
    );
}
