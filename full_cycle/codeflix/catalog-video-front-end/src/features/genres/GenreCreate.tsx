import { Box, Paper, Typography } from "@mui/material";
import { GenreForm } from "./components/GenreForm";
import { useSnackbar } from "notistack";
import {
    useCreateGenreMutation,
    initialState as genreInitialState,
    useGetAllCategoriesQuery, 
} from "./genreSlice";
import { useEffect, useState } from "react";
import { Genre } from "../../types/Genre";

export const GenreCreate = () => {
    const { enqueueSnackbar } = useSnackbar();
    const { data: categories } = useGetAllCategoriesQuery();
    const [createGenre, status] = useCreateGenreMutation();
    const [genreState, setGenreState] = useState<Genre>(genreInitialState);

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setGenreState({ ...genreState, [name]: value });
    }

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        await createGenre({
            id: genreState.id,
            name: genreState.name, 
            categories_ids: genreState.categories?.map((category) => category.id),
        });
    };

    useEffect(() => {
        if (status.isSuccess) {
            enqueueSnackbar("Genre created", { variant: "success" });
        }

        if (status.isError) {
            enqueueSnackbar("Genre not created", { variant: "error" });
        }
    }, [status, enqueueSnackbar]);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Create Genre</Typography>
                    </Box>
                </Box>
                <GenreForm
                    genre={genreState}
                    categories={categories?.data}
                    isLoading={status.isLoading}
                    isDisabled={status.isLoading}
                    handleSubmit={handleSubmit}
                    handleChange={handleChange}
                />
            </Paper>
        </Box>
    );
}
