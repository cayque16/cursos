// import { createTheme } from "@mui/system";
import { createTheme } from "@mui/material/styles";

export const darkTheme = createTheme({
    palette: {
        background: { default: "#222222" },
        mode: "dark",
        primary: { main: "#f5f5f1" },
        secondary: { main: "#e50914" },
        text: { primary: "#f5f5f1" },
    },
});

export const lightTheme = createTheme({
    palette: {
        background: {},
        mode: "light",
        primary: { main: "#e40914" },
        secondary: { main: "#000" },
        text: { primary: "#000" },
    },
});
