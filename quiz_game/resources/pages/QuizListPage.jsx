import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Echo from "laravel-echo";
import { styled } from "@mui/material/styles";
import Grid from "@mui/material/Grid2";
import Box from "@mui/material/Box";
import { Divider } from "@mui/material";

import http from "../utils/http";
import StatCard from "./StatCard";

export default function QuizListPage() {
    const [quizzes, setQuizzes] = useState([]);
    const [leaderboard, setLeaderboard] = useState([]);
    const navigate = useNavigate();
    console.log(leaderboard);

    useEffect(() => {
        // Fetch quizzes
        http.get("/quizzes/available", {
            params: {
                user_id: 11,
            },
        }).then((res) => setQuizzes(res.data));

        // Fetch leaderboard
        http.get("/leaderboard/global").then((res) => setLeaderboard(res.data));

        // Listen to real-time events via Echo
        const echo = new Echo({
            broadcaster: "pusher",
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
        });

        const channel = echo.channel("quiz");
        channel.listen(".quiz.completed", (e) => {
            // Update leaderboard in real-time
            setLeaderboard((prev) => [...prev, e.leaderboard]);
        });

        // Cleanup listener when component unmounts
        return () => {
            channel.stopListening(".quiz.completed");
            echo.disconnect();
        };
    }, []);
    return (
        <Box
            sx={{
                width: "100%",
                maxWidth: { sm: "100%", md: "1700px" },
                margin: "auto",
                padding: 2,
                display: "flex",
                flexDirection: "column",
                gap: 2,
            }}
        >
            <Divider>LeaderBoard</Divider>
            <Box
                sx={{
                    width: "100%",
                }}
            >
                <Grid container spacing={2} columns={12} sx={{ mb: (theme) => theme.spacing(2) }}>
                    {leaderboard.map(({ user_id, user_name, total_score }, index) => (
                        <Grid key={user_id} size={{ xs: 12, sm: 6, lg: 3 }}>
                            <StatCard username={user_name} score={total_score} rank={index + 1} />
                        </Grid>
                    ))}
                </Grid>
            </Box>
            <Divider>Quizzes</Divider>
            <Box
                sx={{
                    width: "100%",
                }}
            >
                <Grid container spacing={2} columns={12}>
                    {quizzes.map((quiz) => (
                        <Grid key={quiz.id} item xs={12} sm={6} lg={4}>
                            <Box
                                sx={{
                                    border: "1px solid #ccc",
                                    padding: "16px",
                                    borderRadius: "8px",
                                    cursor: "pointer",
                                    '&:hover': { boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)' }
                                }}
                                onClick={() => navigate(`/quiz/${quiz.id}`)}
                            >
                                <h3>{quiz.title}</h3>
                                <p>{quiz.description}</p>
                                <p>Duration: {quiz.duration} seconds</p>
                            </Box>
                        </Grid>
                    ))}
                </Grid>
            </Box>
        </Box>
    );
}
