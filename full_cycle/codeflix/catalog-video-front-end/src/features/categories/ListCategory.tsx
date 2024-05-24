import { Box, Button } from "@mui/material";
import { useSnackbar } from "notistack";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAppDispatch } from "../../app/hooks";
import { useDeleteCategoryMutation, useGetCategoriesQuery } from "./categorySlice";
import { CategoriesTable } from "./components/CategoryTable";
import { GridFilterModel } from "@mui/x-data-grid";

export const CategoryList = () => {
    const [page, setPage] = useState(1);
    const [perPage] = useState(10);
    const [search, setSearch] = useState("");
    const [rowsPerPage] = useState([10, 25, 50, 100]);

    const options = { perPage, search, page };

    const { data, isFetching, error } = useGetCategoriesQuery(options);
    const [deleteCategory, deleteCategoryStatus] = useDeleteCategoryMutation();
    const { enqueueSnackbar } = useSnackbar();

    function handleOnPageChange(page: number) {
        console.log(page);
    }

    function handleOnPageSizeChange(perPage: number) {
        console.log(perPage);
    }

    function handleFilterChange(filterMode: GridFilterModel) {
        console.log(filterMode);
    }

    async function handleDeleteCategory(id: string) {
        await deleteCategory({ id });
    }

    useEffect(() => {
        if (deleteCategoryStatus.isSuccess) {
            enqueueSnackbar("Category deleted successfully", { variant: "success" });
        }
        if (deleteCategoryStatus.error) {
            enqueueSnackbar("Category not deleted", { variant: "error" });
        }
    }, [deleteCategoryStatus, enqueueSnackbar]);

    return (
        <Box maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
            <Box display="flex" justifyContent="flex-end">
                <Button
                    variant="contained"
                    color="secondary"
                    component={Link}
                    to="/categories/create"
                    style={{ marginBottom: "1rem" }}
                >
                    New Category
                </Button>
            </Box>
            <CategoriesTable
                data={data}
                perPage={perPage}
                isFetching={isFetching}
                rowsPerPage={rowsPerPage}
                handleDelete={handleDeleteCategory}
                handleOnPageChange={handleOnPageChange}
                handleFilterChange={handleFilterChange}
                handleOnPageSizeChange={handleOnPageSizeChange}
            />            
        </Box>
    );
};