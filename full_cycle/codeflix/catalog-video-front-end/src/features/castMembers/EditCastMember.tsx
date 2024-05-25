import { Box, Paper, Typography } from "@mui/material";
import { useSnackbar } from "notistack";
import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { CastMember } from "../../types/CastMember";
import { useGetCastMemberQuery, useUpdateCastMemberMutation } from "./castMemberSlice";
import { CastMemberForm } from "./components/CastMemberForm";

export const EditCastMember = () => {
    const id = useParams().id ?? "";
    const { data: castMember, isFetching } = useGetCastMemberQuery({ id });
    const [castMemberState, setCastMemberState] = useState<CastMember>({
        id: "",
        name: "",
        type: 1,
        deleted_at: null,
        created_at: "",
        updated_at: "",
    });
    const [updateCastMember, status] = useUpdateCastMemberMutation();
    const { enqueueSnackbar } = useSnackbar();

    function handleChanges(e: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = e.target;
        setCastMemberState({...castMemberState, [name]: value });
    }

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        await updateCastMember(castMemberState);
    }

    useEffect(() => {
        if (castMember) {
            setCastMemberState(castMember.data);
        }
    }, [castMember]);

    useEffect(() => {
        if (status.isSuccess) {
            enqueueSnackbar("Cast member updated", { variant: "success" });
        }
        if (status.isError) {
            enqueueSnackbar("Cast member not updated", { variant: "error" });
        }
    }, [status, enqueueSnackbar]);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Edit Cast Member</Typography>
                    </Box>
                </Box>
                <CastMemberForm
                    handleSubmit={handleSubmit}
                    handleChange={handleChanges}
                    castMember={castMemberState}
                    isLoading={isFetching || status.isLoading}
                    isDisabled={status.isLoading}
                ></CastMemberForm>
            </Paper>
        </Box>
    );
};
