// import { createTheme } from "@mui/system";
import { createTheme } from "@mui/material/styles";

export const appTheme = createTheme({
    palette: {
        background: {
            default: "#222222",
        },
        mode: "dark",
        primary: { main: "#f5f5f1" },
        secondary: { main: "#e50914" },
        text: { primary: "#f5f5f1" },
    },
});
