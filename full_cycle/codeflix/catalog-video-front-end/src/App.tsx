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
import { ProtectedRoute } from "./components/ProtectedRoute";
import Login from "./components/Login";

function App() {
  return (
    <Layout>
      <UploadList/>
      <Routes>
        {/* Login */}
        <Route path="/login" element={<Login />} />
        <Route path="/" element={<ProtectedRoute><CategoryList /></ProtectedRoute>}/>
        {/* Category */}
        <Route path="/categories" element={<ProtectedRoute><CategoryList /></ProtectedRoute>}/>
        <Route path="/categories/create" element={<ProtectedRoute><CategoryCreate /></ProtectedRoute>}/>
        <Route path="/categories/edit/:id" element={<ProtectedRoute><CategoryEdit /></ProtectedRoute>}/>
        {/* Cast Member */}
        <Route path="/cast-members" element={<ProtectedRoute><ListCastMembers /></ProtectedRoute>}/>
        <Route path="/cast-members/create" element={<ProtectedRoute><CreateCastMember /></ProtectedRoute>}/>
        <Route path="/cast-members/edit/:id" element={<ProtectedRoute><EditCastMember /></ProtectedRoute>}/>
        {/* Genre */}
        <Route path="/genres" element={<ProtectedRoute><GenreList /></ProtectedRoute>}/>
        <Route path="/genres/create" element={<ProtectedRoute><GenreCreate /></ProtectedRoute>}/>
        <Route path="/genres/edit/:id" element={<ProtectedRoute><GenreEdit /></ProtectedRoute>}/>
        {/* Videos */}
        <Route path="/videos" element={<ProtectedRoute><VideoList /></ProtectedRoute>}/>
        <Route path="/videos/create" element={<ProtectedRoute><VideoCreate /></ProtectedRoute>}/>
        <Route path="/videos/edit/:id" element={<ProtectedRoute><VideoEdit /></ProtectedRoute>}/>
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
