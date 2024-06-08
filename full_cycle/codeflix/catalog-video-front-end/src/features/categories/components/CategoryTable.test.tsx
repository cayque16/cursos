import { render } from "@testing-library/react";
import { BrowserRouter } from "react-router-dom";
import { CategoriesTable } from "./CategoryTable";

const Props = {
    data: undefined,
    perPage: 10,
    isFetching: false,
    rowsPerPage: [10, 20, 30],
    handleOnPageChange: () => {},
    handleFilterChange: () => {},
    handleOnPageSizeChange: () => {},
    handleDelete: () => {},
};

const mockData = {
    data: [
        {
            id: "123",
            name: "Teste",
            description: "Teste",
            is_active: true,
            deleted_at: "2021",
            created_at: "2021",
            updated_at: "2021",
        },
    ],
    meta: {
        to: 1,
        from: 1,
        last_page: 1,
        path: "http://192.168.2.10:8080/api/cast_members",
        per_page: 1,
        total: 1,
        current_page: 1,
    },
    links: {
    first: "http://192.168.2.10:8080/api/cast_members?page=1",
    last: "http://192.168.2.10:8080/api/cast_members?page=1",
    prev: "",
    next: "",
    },
};

describe("CategoryTable", () => {
    it("should render correctly", () => {
        const { asFragment } = render(<CategoriesTable {...Props} />);

        expect(asFragment()).toMatchSnapshot();
    });
    it("should render CategoriesTable with loading", () => {
        const { asFragment } = render(<CategoriesTable {...Props} isFetching={true} />);

        expect(asFragment()).toMatchSnapshot();
    });
    it("should render CategoriesTable with data", () => {
        const { asFragment } = render(<CategoriesTable {...Props} data={mockData}/>, {
            wrapper: BrowserRouter
        });

        expect(asFragment()).toMatchSnapshot();
    });
    it("should render CategoriesTable with Inactive value", () => {
        const { asFragment } = render(<CategoriesTable {...Props} data={{...mockData, data: [{...mockData.data[0], is_active: false}]}}/>, {
            wrapper: BrowserRouter
        });

        expect(asFragment()).toMatchSnapshot();
    });
});
