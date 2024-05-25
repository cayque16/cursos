import { Typography } from "@mui/material";
import { ThemeProvider } from "@mui/material/styles";
import { Box } from "@mui/system";
import { Route, Routes } from "react-router-dom";
import { Header } from "./components/Header";
import { Layout } from "./components/Layout";
import { appTheme } from "./config/theme";
import { CategoryCreate } from "./features/categories/CreateCategory";
import { CategoryEdit } from "./features/categories/EditCategory";
import { CategoryList } from "./features/categories/ListCategory";
import { SnackbarProvider } from "notistack";
import { ListCastMembers } from "./features/castMembers/ListCastMembers";
import { CreateCastMember } from "./features/castMembers/CreateCastMember";

function App() {
  return (
    <ThemeProvider theme={appTheme}>
      <SnackbarProvider
        autoHideDuration={2000}
        maxSnack={3}
        anchorOrigin={{
          vertical: "top",
          horizontal: "right",
        }}
      >
      <Box
        component="main"
        sx={{
          height: "100vh",
          backgroundColor: (theme) => theme.palette.grey[900],
        }}
      >
        <Header />
        <Layout>
          <Routes>
            <Route path="/" element={<CategoryList />}></Route>
            {/* Category */}
            <Route path="/categories" element={<CategoryList />}></Route>
            <Route path="/categories/create" element={<CategoryCreate />}></Route>
            <Route path="/categories/edit/:id" element={<CategoryEdit />}></Route>
            {/* Cast Member */}
            <Route path="/cast-members" element={<ListCastMembers />}></Route>
            <Route path="/cast-members/create" element={<CreateCastMember />}></Route>
            <Route path="/cast-members/edit/:id" element={<CategoryEdit />}></Route>
            <Route
              path="*"
              element={
                <Box sx={{ color: "white" }}>
                  <Typography variant="h1">404</Typography>
                  <Typography variant="h2">Page not found</Typography>
                </Box>
              }
            />
          </Routes>
        </Layout>
      </Box>
      </SnackbarProvider>
    </ThemeProvider>
  );
}

export default App;
