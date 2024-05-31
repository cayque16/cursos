import { renderWithProviders } from "../../utils/test-utils";
import { CategoryList } from "./ListCategory";

describe("ListCategory", () => {
    it("should render correctly", () => {
        const { asFragment } = renderWithProviders(<CategoryList />);

        expect(asFragment()).toMatchSnapshot();
    });
});
