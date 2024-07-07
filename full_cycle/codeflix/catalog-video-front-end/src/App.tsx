import { Typography } from "@mui/material";
import { Box } from "@mui/system";
import { Route, Routes } from "react-router-dom";
import { Layout } from "./components/Layout";
import { CreateCastMember } from "./features/castMembers/CreateCastMember";
import { EditCastMember } from "./features/castMembers/EditCastMember";
import { ListCastMembers } from "./features/castMembers/ListCastMembers";
import { CategoryCreate } from "./features/categories/CreateCategory";
import { CategoryEdit } from "./features/categories/EditCategory";
import { CategoryList } from "./features/categories/ListCategory";
import { GenreCreate } from "./features/genres/GenreCreate";
import { GenreEdit } from "./features/genres/GenreEdit";
import { GenreList } from "./features/genres/GenreList";
import { UploadList } from "./features/uploads/UploadList";
import { VideoCreate } from "./features/videos/VideoCreate";
import { VideoEdit } from "./features/videos/VideoEdit";
import { VideoList } from "./features/videos/VideoList";

function App() {
  return (
    <Layout>
      <UploadList/>
      <Routes>
        <Route path="/" element={<CategoryList />}></Route>
        {/* Category */}
        <Route path="/categories" element={<CategoryList />}></Route>
        <Route path="/categories/create" element={<CategoryCreate />}></Route>
        <Route path="/categories/edit/:id" element={<CategoryEdit />}></Route>
        {/* Cast Member */}
        <Route path="/cast-members" element={<ListCastMembers />}></Route>
        <Route path="/cast-members/create" element={<CreateCastMember />}></Route>
        <Route path="/cast-members/edit/:id" element={<EditCastMember />}></Route>
        {/* Genre */}
        <Route path="/genres" element={<GenreList />}></Route>
        <Route path="/genres/create" element={<GenreCreate />} />
        <Route path="/genres/edit/:id" element={<GenreEdit />}></Route>
        {/* Videos */}
        <Route path="/videos" element={<VideoList />}></Route>
        <Route path="/videos/create" element={<VideoCreate />} />
        <Route path="/videos/edit/:id" element={<VideoEdit />}></Route>
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
  );
}

export default App;
