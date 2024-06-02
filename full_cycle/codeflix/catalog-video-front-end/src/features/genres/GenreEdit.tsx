import { useSnackbar } from "notistack";
import { useParams } from "react-router-dom";
import { initialState, useGetAllCategoriesQuery, useGetGenreQuery, useUpdateGenreMutation } from "./genreSlice";
import { useEffect, useState } from "react";
import { Genre } from "../../types/Genre";
import { Box, Paper, Typography } from "@mui/material";
import { GenreForm } from "./components/GenreForm";

export const GenreEdit = () => {
    const id = useParams<{ id: string }>().id as string;
    const { data: genre, isFetching } = useGetGenreQuery({ id });
    const { enqueueSnackbar } = useSnackbar();
    const { data: categories } = useGetAllCategoriesQuery();
    const [updateGenre, status] = useUpdateGenreMutation();
    const [genreState, setGenreState] = useState<Genre>(initialState);

    function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = event.target;
        setGenreState((state) => ({ ...state, [name]: value }));
    }

    async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        await updateGenre({
            id: genreState.id,
            name: genreState.name,
            categories_ids: genreState.categories?.map((category) => category.id),
        });
    };

    useEffect(() => {
        if (genre) {
            setGenreState(genre.data);
        }
    }, [genre]);

    useEffect(() => {
        if (status.isSuccess) {
            enqueueSnackbar("Genre updated", { variant: "success" });
        }

        if (status.isError) {
            enqueueSnackbar("Genre not updated", { variant: "error" });
        }
    }, [status, enqueueSnackbar]);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Edit Genre</Typography>
                    </Box>
                </Box>
                <GenreForm
                    genre={genreState}
                    handleSubmit={handleSubmit}
                    handleChange={handleChange}
                    categories={categories?.data}
                    isDisabled={status.isLoading}
                    isLoading={status.isLoading || isFetching}
                />
            </Paper>
        </Box>
    );
};
