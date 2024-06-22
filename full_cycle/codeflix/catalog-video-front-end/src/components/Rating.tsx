import { Box, Typography } from "@mui/material";

const backgroundColors = {
    "L": "#39B549",
    "10": "#20A3D4",
    "12": "#E79738",
    "14": "#E35E00",
    "16": "#D00003",
    "18": "#000000",
}

type Props = {
    rating: "L" | "10" | "12" | "14" | "16" | "18";
}

export function Rating({ rating }: Props) {
    return (
        <Box
            sx={{
                "& > :first-child": { mr: 0 },
                width: 40,
                height: 40,
                borderRadius: "4px",
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
                backgroundColor: backgroundColors[rating],
            }}
        >
            <Typography>{rating}</Typography>
        </Box>
    );
}
