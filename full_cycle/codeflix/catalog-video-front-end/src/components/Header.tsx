import { AppBar, Box, Button, IconButton, Toolbar, Typography } from "@mui/material";
import MenuIcon from '@mui/icons-material/Menu';
import Brightness4ICon from '@mui/icons-material/Brightness4';
import Brightness7ICon from '@mui/icons-material/Brightness7';
import { keycloak } from "../keycloakConfig";

type HeaderProps = {
  toggle: () => void;
  theme: string;
  handleDrawerToggle?: () => void;
};

export function Header({ toggle, theme, handleDrawerToggle }: HeaderProps) {
  return (
    <Box>
      <Toolbar>
        <IconButton
          size="large"
          edge="start"
          color="inherit"
          aria-label="menu"
          onClick={handleDrawerToggle}
          sx={{ mr: 2, display: { sm: "none" } }}
        >
          <MenuIcon />
        </IconButton>
        <Box sx={{ flexGrow: 1 }} />
        <IconButton sx={{ ml: 1 }} onClick={toggle} color="inherit">
          {theme === "dark" ? <Brightness7ICon /> : <Brightness4ICon />}
        </IconButton>

        <Button color="inherit" onClick={() => keycloak.logout()}>
          Logout
        </Button>
      </Toolbar>
    </Box>
  );
}