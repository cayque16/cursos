import { Autocomplete, Box, Button, FormControl, Grid, TextField } from "@mui/material";
import { Video } from "../../../types/Video";
import { Genre } from "../../../types/Genre";
import { Category } from "../../categories/categorySlice";
import { CastMember } from "../../../types/CastMember";
import { Link } from "react-router-dom";

type Props = {
    video: Video,
    genres?: Genre[],
    categories?: Category[],
    castMembers?: CastMember[],
    isLoading: boolean,
    isDisabled: boolean,
    handleSubmit: (e: React.FormEvent<HTMLFormElement>) => void,
    handleChange: (e: React.ChangeEvent<HTMLInputElement>) => void,
};

export function VideoForm({
    video,
    genres,
    categories,
    castMembers,
    isLoading = false,
    isDisabled = false,
    handleSubmit,
    handleChange,
}: Props) {
    return (
        <Box p={2}>
            <form onSubmit={handleSubmit}>
                <Grid container spacing={3}>
                    <Grid item xs={12}>
                        <FormControl fullWidth>
                            <TextField
                                required
                                name="title"
                                label="Title"
                                value={video.title}
                                disabled={isDisabled}
                                onChange={handleChange}
                                inputProps={{ "data-testid" : "title"}}
                            />
                        </FormControl>
                    </Grid>
                   
                    <Grid item xs={12}>
                            <Box display="flex" gap={2}>
                                <Button variant="contained" component={Link} to="/videos">
                                    Back
                                </Button>

                                <Button
                                    type="submit"
                                    variant="contained"
                                    color="secondary"
                                    disabled={isDisabled || isLoading}
                                >
                                    {isLoading ? "Loading..." : "Save"}
                                </Button>
                            </Box>
                        </Grid>
                </Grid>
            </form>
        </Box>
    );
}
