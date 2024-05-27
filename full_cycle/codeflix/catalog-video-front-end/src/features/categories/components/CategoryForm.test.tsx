import { render } from "@testing-library/react";
import { BrowserRouter } from "react-router-dom";
import { CategoryForm } from "./CategoryForm";

const Props = {
    category: {
        id: "1",
        name: "Teste",
        description: "Teste",
        is_active: true,
        deleted_at: null,
        created_at: "2021",
        updated_at: "2021",
    },
    isDisabled: false,
    isLoading: false,
    handleSubmit: () => {},
    handleChange: () => {},
    handleToggle: () => {},
}

describe("CategoryForm", () => {
    it("should render correctly", () => {
        const { asFragment } = render(<CategoryForm  {...Props}/>, {
            wrapper: BrowserRouter,
        });

        expect(asFragment()).toMatchSnapshot();
    });
    it("should render CategoryForm with loading", () => {
        const { asFragment } = render(<CategoryForm  {...Props} isLoading={true} isDisabled={true}/>, {
            wrapper: BrowserRouter,
        });

        expect(asFragment()).toMatchSnapshot();
    });
});