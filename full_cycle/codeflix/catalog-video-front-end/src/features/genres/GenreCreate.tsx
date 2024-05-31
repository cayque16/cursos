import { Box, Paper, Typography } from "@mui/material";
import { GenreForm } from "./components/GenreForm";

export const GenreCreate = () => {
    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Create Genre</Typography>
                    </Box>
                </Box>
                <GenreForm
                    genre={{}}
                    categories={[]}
                    isLoading={false}
                    isDisabled={false}
                    handleSubmit={function noRefCheck() {}}
                    handleChange={function noRefCheck() {}}
                />
            </Paper>
        </Box>
    );
}
