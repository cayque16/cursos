import DeleteIcon from "@mui/icons-material/Delete";
import { Box, Button, IconButton, Typography } from "@mui/material";
import { DataGrid, GridColDef, GridRenderCellParams, GridRowsProp, GridToolbar } from "@mui/x-data-grid";
import { useSnackbar } from "notistack";
import { useEffect } from "react";
import { Link } from "react-router-dom";
import { useAppDispatch } from "../../app/hooks";
import { useDeleteCategoryMutation, useGetCategoriesQuery } from "./categorySlice";

export const CategoryList = () => {
    const { data, isFetching, error } = useGetCategoriesQuery();
    const [deleteCategory, deleteCategoryStatus] = useDeleteCategoryMutation();

    const dispatch = useAppDispatch();
    const { enqueueSnackbar } = useSnackbar();

    const componentsProps = {
        toolbar: {
            showQuickFilter: true,
            quickFilterProps: { debounceMs: 500}
        },
    };

    //use categories to crate rows
    const rows: GridRowsProp = data ? data.data.map((category) => ({
        id: category.id,
        name: category.name,
        description: category.description,
        createdAt: new Date(category.created_at).toLocaleDateString("pt-BR"),
        isActive: category.is_active,
    })) : [];

    const columns: GridColDef[] = [
        {
            field: "name",
            headerName: "Name", 
            flex: 1,
            renderCell: renderNameCell,
        },
        {
            field: "isActive",
            headerName: "Active",
            flex: 1,
            renderCell: renderIsActiveCell,
        },
        {
            field: "createdAt",
            headerName: "Created At", 
            flex: 1
        },
        {
            field: "id",
            headerName: "Actions",
            type: "string",
            flex: 1,
            renderCell: renderActionsCell,
        },
    ];

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

    function renderActionsCell(params: GridRenderCellParams) {
        return (
            <IconButton
                color="secondary"
                onClick={() => handleDeleteCategory(params.value)}            
                aria-label="delete"
            >
                <DeleteIcon/>           
            </IconButton>
        );
    }

    function renderNameCell(rowData: GridRenderCellParams) {
        return (
            <Link
                style={{ textDecoration: "none"}}
                to={`/categories/edit/${rowData.id}`}
            >
                <Typography color="primary">{rowData.value}</Typography>
            </Link>
        );
    }

    function renderIsActiveCell(rowData: GridRenderCellParams) {
        return (
            <Typography color={rowData.value ? "primary" : "secondary"}>
                {rowData.value ? "Active" : "Inactive"}
            </Typography>
        );
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

            <Box style={{ display: "flex", height: 600 }}>
                <DataGrid
                    rows={rows}
                    columns={columns}
                    disableColumnFilter={true}
                    disableColumnSelector={true}
                    disableDensitySelector={true}
                    disableSelectionOnClick={true}
                    componentsProps={componentsProps}
                    components={{ Toolbar: GridToolbar }}
                    rowsPerPageOptions={[10, 20, 50, 100]}
                />
            </Box>
        </Box>
    );
};