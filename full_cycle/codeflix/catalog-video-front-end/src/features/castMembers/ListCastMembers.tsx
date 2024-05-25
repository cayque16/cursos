import { useEffect, useState } from "react"
import { useDeleteCastMemberMutation, useGetCastMembersQuery } from "./castMemberSlice";
import { GridFilterModel } from "@mui/x-data-grid";
import { Box, Button, Typography } from "@mui/material";
import { Link } from "react-router-dom";
import { useSnackbar } from "notistack";
import { CastMembersTable } from "./components/CastMembersTable";

export const ListCastMembers = () => {
    const { enqueueSnackbar } = useSnackbar();
    const [page, setPage] = useState(1);
    const [filter, setFilter] = useState("");
    const [perPage, setPerPage] = useState(10);
    const [rowsPerPage] = useState([10, 25, 50, 100]);
    const options = { page, perPage, filter };

    const { data, isFetching, error } = useGetCastMembersQuery(options);
    const [deleteCastMember, deleteCastMemberStatus] = useDeleteCastMemberMutation();

    async function handleDeleteCastMember(id: string) {
        await deleteCastMember({ id });
    }

    function handleOnPageChange(page: number) {
        setPage(page + 1);
    }

    function handleOnPageSizeChange(perPage: number) {
        setPerPage(perPage);
    }

    function handleFilterChange(filterModel: GridFilterModel) {
        if (filterModel.quickFilterValues?.length) {
            const filter = filterModel.quickFilterValues.join("");
            options.filter = filter;
            setFilter(filter);
        }
        return setFilter("");
    }

    useEffect(() => {
        if (deleteCastMemberStatus.isSuccess) {
            enqueueSnackbar("Cast Member deleted successfully", { variant: "success" });
        }
        if (deleteCastMemberStatus.error) {
            enqueueSnackbar("Cast Member not deleted", { variant: "error" });
        }
    }, [deleteCastMemberStatus, enqueueSnackbar]);

    if (error) {
        return <Typography variant="h2">Error!</Typography>
    }

    return (
        <Box maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
            <Box display="flex" justifyContent="flex-end">
                <Button
                    variant="contained"
                    color="secondary"
                    component={Link}
                    to="/cast-members/create"
                    style={{ marginBottom: "1rem" }}
                >
                    New Cast Member
                </Button>
            </Box>
            <CastMembersTable 
                data={data}
                perPage={perPage}
                isFetching={isFetching}
                rowsPerPage={rowsPerPage}
                handleDelete={handleDeleteCastMember}
                handleOnPageChange={handleOnPageChange}
                handleFilterChange={handleFilterChange}
                handleOnPageSizeChange={handleOnPageSizeChange}
            />
        </Box>
    );
}
