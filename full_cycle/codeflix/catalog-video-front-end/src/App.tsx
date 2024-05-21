import { Box} from "@mui/system";
import {  ThemeProvider } from "@mui/material/styles";
import * as React from 'react';
import { Header } from "./components/Header";
import { Layout } from "./components/Layout";
import { appTheme } from "./config/theme";
import { Typography } from "@mui/material";
import { Routes, Route, Link } from "react-router-dom";

const Home = () => (
  <Box>
    <Typography variant="h3" component="h1">
      Home
    </Typography>
  </Box>
);

const About = () => (
  <Box>
    <Typography variant="h3" component="h1">
      About test
    </Typography>
  </Box>
);

function App() {
  return (
    <ThemeProvider theme={appTheme}>
      <Box
        component="main"
        sx={{
          height: "100vh",
          backgroundColor: (theme) => theme.palette.grey[900],
        }}
      >
        <Header />
        <Layout>
          <h1>Welcome</h1>
          <Routes>
            <Route path="/" element={<Home />}></Route>
            <Route path="about" element={<About />}></Route>
          </Routes>
        </Layout>
      </Box>
    </ThemeProvider>
  );
}

export default App;
