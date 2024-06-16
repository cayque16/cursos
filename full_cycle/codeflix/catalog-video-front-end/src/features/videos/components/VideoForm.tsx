import { Autocomplete, Box, Button, FormControl, Grid, TextField } from "@mui/material";
import { Video } from "../../../types/Video";
import { Genre } from "../../../types/Genre";
import { CastMember } from "../../../types/CastMember";
import { Link } from "react-router-dom";
import { Category } from "../../../types/Category";
import React from "react";
import { AutoCompleteFields } from "../../../components/AutoCompleteFields";

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
                <Grid container spacing={4}>
                    <Grid item xs={12} md={6} sx={{ "& .MuiTextField-root": { my: 2}} }>
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
                        <FormControl fullWidth>
                            <TextField
                                required
                                multiline
                                minRows={4}
                                name="description"
                                label="Description"
                                value={video.description}
                                disabled={isDisabled}
                                onChange={handleChange}
                                inputProps={{ "data-testid" : "description"}}
                            />
                        </FormControl>
                        
                        <Grid container spacing={2}>
                            <Grid item xs={6}>
                                <FormControl fullWidth>
                                    <TextField
                                        required
                                        name="year_launched"
                                        label="Year Launched"
                                        value={video.year_launched}
                                        disabled={isDisabled}
                                        onChange={handleChange}
                                        inputProps={{ "data-testid" : "year_launched"}}
                                    />
                                </FormControl>
                         </Grid>

                        <Grid item xs={6}>
                            <FormControl fullWidth>
                                <TextField
                                    name="duration"
                                    label="Duration"
                                    value={video.duration}
                                    disabled={isDisabled}
                                    onChange={handleChange}
                                    inputProps={{ "data-testid" : "duration"}}
                                />
                            </FormControl>
                        </Grid>
                        </Grid>
                        <Grid item xs={12}>
                            <AutoCompleteFields
                                name="categories"
                                label="Categories"
                                isLoading={isLoading}
                                isDisabled={isDisabled}
                                values={video.categories}
                                options={categories || []}
                                handleChange={handleChange}
                            />
                        </Grid>
                        <Grid item xs={12}>
                            <AutoCompleteFields
                                name="genres"
                                label="Genres"
                                isLoading={isLoading}
                                isDisabled={isDisabled}
                                values={video.genres}
                                options={genres || []}
                                handleChange={handleChange}
                            />
                        </Grid>
                        <Grid item xs={12}>
                            <AutoCompleteFields
                                name="castMembers"
                                label="Cast Members"
                                isLoading={isLoading}
                                isDisabled={isDisabled}
                                values={video.cast_members}
                                options={castMembers || []}
                                handleChange={handleChange}
                            />
                        </Grid>
                    </Grid>
                    <Grid item xs={12} md={6} sx={{ "&.MuiTextField-root": { my: 2}}}>
                        <h1>Left</h1>
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
