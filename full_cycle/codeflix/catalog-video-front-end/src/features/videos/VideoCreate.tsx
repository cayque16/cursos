import { Box, Paper, Typography } from "@mui/material";
import { useSnackbar } from "notistack";
import { useEffect, useState } from "react";
import { useAppDispatch } from "../../app/hooks";
import { useUniqueCategories } from "../../hooks/useUniqueCategories";
import { FileObject, Video } from "../../types/Video";
import { VideoForm } from "./components/VideoForm";
import { mapVideoToForm } from "./utils";
import { nanoid } from "nanoid";
import { initialState, useCreateVideoMutation, useGetAllCastMembersQuery, useGetAllGenresQuery } from "./videoSlice";
import { addUpload } from "../uploads/UploadSlice";

export const VideoCreate = () => {
    const { enqueueSnackbar } = useSnackbar();
    const { data: genres } = useGetAllGenresQuery();
    const { data: castMembers } = useGetAllCastMembersQuery();
    const [createVideo, status] = useCreateVideoMutation();
    const [videoState, setVideoState] = useState<Video>(initialState);
    const [categories] = useUniqueCategories(videoState, setVideoState);
    const [selectedFiles, setSelectedFiles] = useState<FileObject[]>([]);
    const dispatch = useAppDispatch();

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setVideoState((state) => ({...state, [name]: value }));
    }

    function handleAddFiles({ name, file }: FileObject) {
        setSelectedFiles([...selectedFiles, { name, file }]);
    }

    function handleRemoveFiles(name: string) {
        setSelectedFiles(selectedFiles.filter((file) => file.name !== name));
    }

    function handleSubmitUploads(videoId: string) {
        selectedFiles.forEach(({ file, name })=> {
            const payload = { id: nanoid(), file, videoId, field: name };
            dispatch(addUpload(payload));
        });
    }

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        const { ...payload } = mapVideoToForm(videoState);
        try {
            const { data } = await createVideo(payload).unwrap();
            handleSubmitUploads(data[0].id);
        } catch (e) {
            enqueueSnackbar(`Error creating video`, { variant: "error" });
        }
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
                    categories={categories}
                    castMembers={castMembers?.data}
                    handleAddFiles={handleAddFiles}
                    handleRemoveFiles={handleRemoveFiles}
                />
            </Paper>
        </Box>
    );
};
