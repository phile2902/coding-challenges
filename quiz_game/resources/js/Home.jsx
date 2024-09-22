import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import LoginPage from './../pages/LoginPage';
import QuizListPage from './../pages/QuizListPage';
import QuizPage from './../pages/QuizPage';

const Home = () => {
    return (
        <Router>
            <Routes>
                <Route path="/" element={<LoginPage />} />
                <Route path="/quizzes" element={<QuizListPage />} />
                <Route path="/quiz/:quizId" element={<QuizPage />} />
            </Routes>
        </Router>
    );
};

export default Home;
