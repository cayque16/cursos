import { useSnackbar } from "notistack";
import { useParams } from "react-router-dom";
import { initialState, useGetAllCategoriesQuery, useGetGenreQuery, useUpdateGenreMutation } from "./genreSlice";
import { useState } from "react";
import { Genre } from "../../types/Genre";
import { Box, Paper, Typography } from "@mui/material";

export const GenreEdit = () => {
    const id = useParams<{ id: string }>().id as string;
    const { data: genre, isFetching } = useGetGenreQuery({ id });
    const { enqueueSnackbar } = useSnackbar();
    const { data: categories } = useGetAllCategoriesQuery();
    const [updateGenre, status] = useUpdateGenreMutation();
    const [genreState, setGenreState] = useState<Genre>(initialState);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Edit Genre</Typography>
                    </Box>
                </Box>
                {/* <GenreForm
                    genre={genreState}
                    categories={categories?.data}
                    isLoading={status.isLoading}
                    isDisabled={status.isLoading}
                    handleSubmit={handleSubmit}
                    handleChange={handleChange}
                /> */}
            </Paper>
        </Box>
    );
};
