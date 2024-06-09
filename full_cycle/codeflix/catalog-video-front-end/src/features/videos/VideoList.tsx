import { Box, Button } from "@mui/material";
import { Link } from "react-router-dom";
import { VideoTable } from "./components/VideoTable";

export const VideoList = () => {
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

            {/* <VideoTable
                data={data}
                isFetching={isFetching}
                perPage={options.perPage}
                rowsPerPage={options.rowsPerPage}
                handleDelete={handleDeleteVideo}
                handleOnPageChange={handleOnPageChange}
                handleOnPageSizeChange={handleOnPageSizeChange}
                handleFilterChange={handleFilterChange}
            /> */}
        </Box>
    );
};