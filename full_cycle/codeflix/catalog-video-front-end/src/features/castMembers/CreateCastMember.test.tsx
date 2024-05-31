import { render } from "@testing-library/react";
import { renderWithProviders } from "../../utils/test-utils";
import { CreateCastMember } from "./CreateCastMember";

describe("CreateCastMember", () => {
    it("should render correctly", () => {
        const { asFragment } = renderWithProviders(<CreateCastMember />);

        expect(asFragment()).toMatchSnapshot();
    });
});
