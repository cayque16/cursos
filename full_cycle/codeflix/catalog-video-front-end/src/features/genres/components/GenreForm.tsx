import { Autocomplete, Box, Button, FormControl, Grid, TextField } from "@mui/material";
import { Category } from "../../../types/Category";
import { Link } from "react-router-dom";
import { Genre } from "../../../types/Genre";

type Props = {
    genre: Genre,
    categories: Category[] | undefined,
    isLoading: boolean,
    isDisabled: boolean,
    handleSubmit: (e: React.FormEvent<HTMLFormElement>) => void,
    handleChange: (e: React.ChangeEvent<HTMLInputElement>) => void,
};

export function GenreForm({
    genre,
    categories,
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
                                name="name"
                                label="Name"
                                value={genre.name}
                                disabled={isDisabled}
                                onChange={handleChange}
                                inputProps={{ "data-testid" : "name"}}
                            />
                        </FormControl>
                    </Grid>
                    <Grid item xs={12}>
                        <Autocomplete
                            disablePortal
                            multiple
                            loading={isLoading}
                            options={categories || []}
                            value={genre.categories}
                            disabled={isDisabled || !categories}
                            getOptionLabel={(option) => option.name}
                            renderOption={(props, option) => (
                                <li {...props} key={option.id}>
                                    {option.name}
                                </li>
                            )}
                            onChange={(_, value) => {
                                handleChange({ target: { name: "categories", value }} as any);
                            }}
                            renderInput={(params) => (
                                <TextField
                                    {...params}
                                    label="Categories"
                                    data-testid="categories-input"
                                />
                            )}
                        />
                    </Grid>
                    <Grid item xs={12}>
                            <Box display="flex" gap={2}>
                                <Button variant="contained" component={Link} to="/genres">
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
