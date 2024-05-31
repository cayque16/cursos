import { rest } from "msw";
import { setupServer } from "msw/node";

import { renderWithProviders, screen, waitFor } from "../../utils/test-utils";
import { CategoryList } from "./ListCategory";
import { baseUrl } from "../api/apiSlice";
import { categoryResponse } from "./mocks";

export const handlers = [
    rest.get(`${baseUrl}/categories`, (_, res, ctx) => {
        return res(ctx.json(categoryResponse), ctx.delay(150));
    }),
];

const server = setupServer(...handlers);

describe("ListCategory", () => {
    afterAll(() => server.close());
    beforeAll(() => server.listen());
    afterEach(() => server.resetHandlers());

    it("should render correctly", () => {
        const { asFragment } = renderWithProviders(<CategoryList />);
        expect(asFragment()).toMatchSnapshot();
    });

    it("should render loading state", () => {
        renderWithProviders(<CategoryList />);
        const loading = screen.getByRole("progressbar");
        expect(loading).toBeInTheDocument();
    });

    it("should render success state", async () => {
        renderWithProviders(<CategoryList/>);

        await waitFor(() => {
            const name = screen.getByText("Experimental");
            expect(name).toBeInTheDocument();
        });
    });

    it("should render error state", async () => {
        server.use(
            rest.get(`${baseUrl}/categories`, (_, res, ctx) => {
                return res(ctx.status(500));
            })
        );

        renderWithProviders(<CategoryList />);

        await waitFor(() => {
            const error = screen.getByText("Error fetching categories");
            expect(error).toBeInTheDocument();
        });
    });
});
