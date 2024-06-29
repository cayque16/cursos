import { AppBar, Box, Button, IconButton, Toolbar, Typography } from "@mui/material";
import MenuIcon from '@mui/icons-material/Menu';

export function Header({ toggle }: { toggle: () => void }) {
    return (
        <Box sx={{ flexGrow: 1 }}>
          <AppBar position="static">
            <Toolbar>
              <IconButton
                size="large"
                edge="start"
                color="inherit"
                aria-label="menu"
                sx={{ mr: 2 }}
              >
                <MenuIcon />
              </IconButton>
              <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
                Codeflix
              </Typography>

              <Button color="inherit" onClick={toggle}>
                Toggle theme
              </Button>

              <Button color="inherit">Login</Button>
            </Toolbar>
          </AppBar>
        </Box>
      );
}