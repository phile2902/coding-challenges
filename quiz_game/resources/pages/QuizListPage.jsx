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
        axios.get('/api/quizzes/available').then((res) => setQuizzes(res.data));

        // Fetch leaderboard
        axios.get('/api/leaderboard/global').then((res) => setLeaderboard(res.data));

        // Listen to real-time events
        const echo = new Echo({ broadcaster: 'pusher', key: process.env.MIX_PUSHER_APP_KEY });
        echo.channel('global').listen('UserSubmittedQuiz', (e) => {
            // Update leaderboard in real time
            setLeaderboard((prev) => [...prev, e]);
        });
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
