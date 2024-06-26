import { Box, Button, Typography } from "@mui/material";
import { GridFilterModel } from "@mui/x-data-grid";
import { useSnackbar } from "notistack";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useDeleteCategoryMutation, useGetCategoriesQuery } from "./categorySlice";
import { CategoriesTable } from "./components/CategoryTable";

export const CategoryList = () => {
    const [page, setPage] = useState(1);
    const [perPage, setPerPage] = useState(10);
    const [filter, setFilter] = useState("");
    const [rowsPerPage] = useState([10, 25, 50, 100]);

    const options = { perPage, filter, page };

    const { data, isFetching, error } = useGetCategoriesQuery(options);
    const [deleteCategory, deleteCategoryStatus] = useDeleteCategoryMutation();
    const { enqueueSnackbar } = useSnackbar();

    function handleOnPageChange(page: number) {
        setPage(page + 1);
    }

    function handleOnPageSizeChange(perPage: number) {
        setPerPage(perPage);
    }

    function handleFilterChange(filterModel: GridFilterModel) {
        if (filterModel.quickFilterValues?.length) {
            const filter = filterModel.quickFilterValues.join("");
            return setFilter(filter);
        }

        return setFilter("");
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

    if (error) {
        return <Typography>Error fetching categories</Typography>
    }

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