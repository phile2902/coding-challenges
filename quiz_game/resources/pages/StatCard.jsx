import * as React from "react";
import Card from "@mui/material/Card";
import CardContent from "@mui/material/CardContent";
import Stack from "@mui/material/Stack";
import Typography from "@mui/material/Typography";

function StatCard({ username, score, rank }) {
    return (
        <Card variant="outlined" sx={{ height: "100%", flexGrow: 1 }}>
            <CardContent>
                <Typography component="h2" variant="subtitle2" gutterBottom>
                    Rank {rank}: {username}
                </Typography>
                <Stack
                    direction="column"
                    sx={{ justifyContent: "space-between", flexGrow: "1", gap: 1 }}
                >
                    <Stack sx={{ justifyContent: "space-between" }}>
                        <Stack
                            direction="row"
                            sx={{ justifyContent: "space-between", alignItems: "center" }}
                        >
                            <Typography variant="h4" component="p">
                                {score}
                            </Typography>
                        </Stack>
                    </Stack>
                </Stack>
            </CardContent>
        </Card>
    );
}

export default StatCard;
