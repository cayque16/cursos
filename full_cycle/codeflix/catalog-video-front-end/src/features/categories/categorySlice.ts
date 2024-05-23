import { createSlice } from "@reduxjs/toolkit";
import { RootState } from "../../app/store";

export interface Category {
    id: string;
    name: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    deleted_at: null | string;
    description: null | string;
}

const category: Category = {
    id: "1234-1234-1235",
    name: "Test 4",
    description: "Test",
    is_active: true,
    deleted_at: null,
    created_at: "2020-01-01",
    updated_at: "2020-01-01",
}

export const initialState = [
    category,
    { ...category, id: "1234-1234-1236", name: "Test 1"},
    { ...category, id: "1234-1234-1237", name: "Test 2"},
    { ...category, id: "1234-1234-1238", name: "Test 3"},
];

const categoriesSlice = createSlice({
    name: "categories",
    initialState: initialState,
    reducers: {
        createCategory(state, action) {
            state.push(action.payload);
        },
        updateCategory(state, action) {
            // find index on state of category to update
            const index = state.findIndex(
                (category) => category.id === action.payload.id
            );
            console.log(action.payload);
            // update category on state
            state[index] = action.payload;
        },
        deleteCategory(state, action) {
            // find index on state of category to delete
            const index = state.findIndex(
                (category) => category.id === action.payload.id
            );
            // delete category on state
            state.splice(index, 1);
        },
    },
});

// Selectors
export const selectCategories = (state: RootState) => state.categories;
// select category by id
export const selectCategoryById = (state: RootState, id: string) => {
    const category = state.categories.find((category) => category.id === id);

    return (
        category || {
            id: "",
            name: "",
            is_active: false,
            created_at: "",
            updated_at: "",
            deleted_at: null,
            description: null,
        }
    );
};


export default categoriesSlice.reducer;
export const { createCategory, updateCategory, deleteCategory } = 
    categoriesSlice.actions;
