import { render } from "@testing-library/react";
import { CastMemberForm } from "./CastMemberForm";
import { BrowserRouter } from "react-router-dom";

const Props = {
    castMember: {
        id: "1",
        name: "Teste",
        type: 1,
        deleted_at: null,
        created_at: "2021",
        updated_at: "2021",
    },
    isDisabled: false,
    isLoading: false,
    handleSubmit: jest.fn(),
    handleChange: jest.fn(),
}

describe("CastMemberForm", () => {
    it("should render correctly", () => {
        const { asFragment } = render(<CastMemberForm  {...Props}/>, {
            wrapper: BrowserRouter,
        });

        expect(asFragment()).toMatchSnapshot();
    });
})