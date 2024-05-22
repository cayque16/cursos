import { createSlice } from "@reduxjs/toolkit";
import { RootState } from "../../app/store";

interface Category {
    id: string;
    name: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    deleted_at: null | string;
    description: null | string;
}

const category: Category = {
    id: "1234-1234-1234",
    name: "Test",
    description: "Test",
    is_active: true,
    deleted_at: null,
    created_at: "2020-01-01",
    updated_at: "2020-01-01",
}

export const initialState = [
    category,
    { ...category, id: "1234-1234-1234", name: "Test"},
    { ...category, id: "1234-1234-1234", name: "Test"},
    { ...category, id: "1234-1234-1234", name: "Test"},
];

const categoriesSlice = createSlice({
    name: "categories",
    initialState: initialState,
    reducers: {
        createCategory(state, action) {},
        updateCategory(state, action) {},
        deleteCategory(state, action) {},
    },
});

// Selectors
export const selectCategories = (state: RootState) => state.categories;

export default categoriesSlice.reducer;
