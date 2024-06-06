import { Box, Button, Typography } from "@mui/material";
import { Link } from "react-router-dom";
import { GenreTable } from "./components/GenreTable";
import { useSnackbar } from "notistack";
import { useEffect, useState } from "react";
import { useDeleteGenreMutation, useGetGenresQuery } from "./genreSlice";
import { GridFilterModel } from "@mui/x-data-grid";

export const GenreList = () => {
    const { enqueueSnackbar } = useSnackbar();
    const [options, setOptions] = useState({
        page: 1,
        perPage: 10,
        filter: "",
        rowsPerPage: [10, 20, 30],
    });
    const { data, isFetching, error } = useGetGenresQuery(options);
    const [deleteGenre, deleteGenreStatus] = useDeleteGenreMutation();

    function handleOnPageChange(page: number) {
        setOptions((state) => ({ ...state, page}));
    }

    function handleOnPageSizeChange(perPage: number) {
        setOptions((state) => ({...state, perPage}));
    }

    function handleFilterChange(filterModel: GridFilterModel) {
        if (!filterModel.quickFilterValues?.length) {
            return setOptions({...options, filter: ""});
        }
        const filter = filterModel.quickFilterValues.join("");
        setOptions({ ...options, filter });
    }

    async function handleDeleteGenre(id: string) {
        await deleteGenre({ id });
    }

    useEffect(() => {
        if (deleteGenreStatus.isSuccess) {
            enqueueSnackbar("Genre deleted successfully", { variant: "success" });
        }
        if (deleteGenreStatus.error) {
            enqueueSnackbar("Genre not deleted", { variant: "error" });
        }
    }, [deleteGenreStatus, enqueueSnackbar]);

    if (error) {
        return <Typography>Error fetching genres</Typography>
    }

    return (
        <Box maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
            <Box display="flex" justifyContent="flex-end">
                <Button
                    variant="contained"
                    color="secondary"
                    component={Link}
                    to="/genres/create"
                    style={{ marginBottom: "1rem" }}
                >
                    New Genre
                </Button>
            </Box>

            <GenreTable
                data={data}
                isFetching={isFetching}
                perPage={options.perPage}
                rowsPerPage={options.rowsPerPage}
                handleDelete={handleDeleteGenre}
                handleOnPageChange={handleOnPageChange}
                handleOnPageSizeChange={handleOnPageSizeChange}
                handleFilterChange={handleFilterChange}
            />
        </Box>
    );
}
