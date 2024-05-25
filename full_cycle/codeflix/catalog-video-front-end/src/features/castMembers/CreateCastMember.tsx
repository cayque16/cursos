import { useEffect, useState } from "react";
import { CastMember } from "../../types/CastMember";
import { useCreateCastMemberMutation } from "./castMemberSlice";
import { useSnackbar } from "notistack";
import { Box, Paper, Typography } from "@mui/material";
import { CastMemberForm } from "./components/CastMemberForm";

export const CreateCastMember = () => {
    const [castMemberState, setCastMemberState] = useState<CastMember>({
        id: "",
        name: "",
        type: 1,
        deleted_at: null,
        created_at: "",
        updated_at: "",
    });
    const [crateCastMember, status] = useCreateCastMemberMutation();
    const { enqueueSnackbar } = useSnackbar();

    function handleChanges(e: React.ChangeEvent<HTMLInputElement>) {
        const { name, value } = e.target;
        setCastMemberState({...castMemberState, [name]: value });
    }

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        await crateCastMember(castMemberState);
    }

    useEffect(() => {
        if (status.isSuccess) {
            enqueueSnackbar("Cast member created successfully", { variant: "success" });
        }
        if (status.isError) {
            enqueueSnackbar("Cast member not created", { variant: "error" });
        }
    }, [status, enqueueSnackbar]);

    return (
        <Box>
            <Paper>
                <Box p={2}>
                    <Box mb={2}>
                        <Typography variant="h4">Create Cast Member</Typography>
                    </Box>
                </Box>
                <CastMemberForm
                    handleSubmit={handleSubmit}
                    handleChange={handleChanges}
                    castMember={castMemberState}
                    isLoading={status.isLoading}
                    isDisabled={status.isLoading}
                ></CastMemberForm>
            </Paper>
        </Box>
    );
};
