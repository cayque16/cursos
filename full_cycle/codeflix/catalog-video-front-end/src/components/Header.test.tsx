import { render } from "@testing-library/react";
import { Header } from "./Header";

describe("Header", () => {
    it("should render correctly", () => {
        const { asFragment } = render(<Header theme="dark" toggle={() => {}}/>);

        expect(asFragment()).toMatchSnapshot();
    });
});
