import { renderWithProviders } from "../../../utils/test-utils";
import { GenreForm } from "./GenreForm";

const Props = {
  genre: {
    id: "1",
    name: "Action",
    isActive: true,
    deleted_at: null,
    created_at: "2021-09-01T00:00:00.000000Z",
    updated_at: "2021-09-01T00:00:00.000000Z",
    categories: [],
    description: "Action",
    pivot: {
      genre_id: "1",
      category_id: "1",
    },
  },
  categories: [],
  isDisabled: false,
  isLoading: false,
  handleSubmit: () => {},
  handleChange: () => {},
};

const mockData = {
  data: [
    {
      id: "1",
      name: "test",
      isActive: true,
      deleted_at: null,
      created_at: "2021-09-01T00:00:00.000000Z",
      updated_at: "2021-09-01T00:00:00.000000Z",
      categories: [
        {
          id: "1233",
          name: "alore",
          deleted_at: "",
          is_active: true,
          created_at: "",
          updated_at: "",
          description: "",
        },
      ],
      description: "test",
      pivot: {
        genre_id: "1",
        category_id: "1",
      },
    },
  ],
  links: {
    first: "http://192.168.2.10:8080/api/genres?page=1",
    last: "http://192.168.2.10:8080/api/genres?page=1",
    prev: "",
    next: "",
  },
  meta: {
    current_page: 1,
    from: 1,
    last_page: 1,
    path: "http://192.168.2.10:8080/api/genres",
    per_page: 15,
    to: 1,
    total: 1,
  },
};

describe("GenreForm", () => {
  it("should render correctly", () => {
    const { asFragment } = renderWithProviders(<GenreForm {...Props} />);

    expect(asFragment()).toMatchSnapshot();
  });

  it("should render GenreForm with loading", () => {
    const { asFragment } = renderWithProviders(
      <GenreForm {...Props} isLoading={true} isDisabled={true} />
    );

    expect(asFragment()).toMatchSnapshot();
  });

  it("should render GenreForm with data", () => {
    const { asFragment } = renderWithProviders(
      <GenreForm {...Props} genre={mockData.data[0]} />
    );

    expect(asFragment()).toMatchSnapshot();
  });
});