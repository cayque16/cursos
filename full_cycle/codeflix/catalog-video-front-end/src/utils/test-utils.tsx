import React, { PropsWithChildren } from "react";
import { render } from "@testing-library/react";
import { RenderOptions } from "@testing-library/react";
import { configureStore, PreloadedState } from "@reduxjs/toolkit";
import { Provider } from "react-redux";

import type { RootState } from "../app/store";
import { apiSlice } from "../features/api/apiSlice";
import { castMembersApiSlice } from "../features/castMembers/castMemberSlice";
import { BrowserRouter } from "react-router-dom";
import { SnackbarProvider } from "notistack";


interface ExtendedRenderOptions extends Omit<RenderOptions, "queries"> {
    preloadedState?: PreloadedState<RootState>;
    store?: ReturnType<typeof configureStore>;
}

export function renderWithProviders(
    ui: React.ReactElement,
    {
        store = configureStore({
            reducer: {
                [castMembersApiSlice.reducerPath]: apiSlice.reducer,
            },
        }),
        ...renderOptions
    }: ExtendedRenderOptions = {}
) {
    function Wrapper({ children }: PropsWithChildren<{}>): JSX.Element {
        return (
            <Provider store={store}>
              <BrowserRouter>
                <SnackbarProvider>{children}</SnackbarProvider>
              </BrowserRouter>
            </Provider>
          );
    }

    return { store, ...render(ui, { wrapper: Wrapper, ...renderOptions }) };
}
