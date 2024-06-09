import { Box, Button, Typography } from "@mui/material";
import { Link } from "react-router-dom";
import { VideoTable } from "./components/VideoTable";
import { useState } from "react";
import { GridFilterModel } from "@mui/x-data-grid";
import { useGetVideosQuery } from "./videoSlice";

export const VideoList = () => {
    const [options, setOptions] = useState({
        page: 1,
        perPage: 10,
        rowsPerPage: [10, 25, 50, 100],
        filter: "",
    });

    const { data, isFetching, error } = useGetVideosQuery(options);

    if (error) {
        return <Typography>Error fetching videos</Typography>
    }

    function handleDeleteVideo(id: string) {}

    function handleOnPageChange(page: number) {
        setOptions({...options, page: page + 1});
    }

    function handleOnPageSizeChange(perPage: number) {
        setOptions({...options, perPage});
    }

    function handleFilterChange(filterModel: GridFilterModel) {
        if (!filterModel.quickFilterValues?.length) {
            return setOptions({...options, filter: ""});
        }
        const filter = filterModel.quickFilterValues.join("");
        setOptions({...options, filter});
    }

    return (
        <Box maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
            <Box display="flex" justifyContent="flex-end">
                <Button
                    variant="contained"
                    color="secondary"
                    component={Link}
                    to="/videos/create"
                    style={{ marginBottom: "1rem" }}
                >
                    New Video
                </Button>
            </Box>

            <VideoTable
                data={data}
                isFetching={isFetching}
                perPage={options.perPage}
                rowsPerPage={options.rowsPerPage}
                handleDelete={handleDeleteVideo}
                handleOnPageChange={handleOnPageChange}
                handleOnPageSizeChange={handleOnPageSizeChange}
                handleFilterChange={handleFilterChange}
            />
        </Box>
    );
};