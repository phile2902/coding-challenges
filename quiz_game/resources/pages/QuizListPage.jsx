import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import Echo from 'laravel-echo';

const QuizListPage = () => {
    const [quizzes, setQuizzes] = useState([]);
    const [leaderboard, setLeaderboard] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        // Fetch quizzes
        axios.get('/api/v1/quizzes/available').then((res) => setQuizzes(res.data));

        // Fetch leaderboard
        axios.get('/api/v1/leaderboard/global').then((res) => setLeaderboard(res.data));

        // Listen to real-time events via Echo
        const echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
        });

        const channel = echo.channel('global');
        channel.listen('UserSubmittedQuiz', (e) => {
            // Update leaderboard in real-time
            setLeaderboard((prev) => [...prev, e]);
        });

        // Cleanup listener when component unmounts
        return () => {
            channel.stopListening('UserSubmittedQuiz');
            echo.disconnect();
        };
    }, []);

    return (
        <div>
            <h2>Available Quizzes</h2>
            <ul>
                {quizzes.map((quiz) => (
                    <li key={quiz.id} onClick={() => navigate(`/quiz/${quiz.id}`)}>{quiz.title}</li>
                ))}
            </ul>

            <div style={{ position: 'absolute', top: 0, right: 0 }}>
                <h3>Leaderboard</h3>
                <ul>
                    {leaderboard.map((entry, index) => (
                        <li key={index}>{entry.user_id}: {entry.total_score}</li>
                    ))}
                </ul>
            </div>
        </div>
    );
};

export default QuizListPage;
