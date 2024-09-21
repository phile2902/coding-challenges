import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';
import Echo from 'laravel-echo';

const QuizPage = () => {
    const { quizId } = useParams();
    const [questions, setQuestions] = useState([]);
    const [score, setScore] = useState(0);
    const [correctAnswers, setCorrectAnswers] = useState(0);
    const [incorrectAnswers, setIncorrectAnswers] = useState(0);
    const [leftQuestions, setLeftQuestions] = useState(0);
    const [tempScore, setTempScore] = useState(0);
    const [timer, setTimer] = useState(1800); // 30 minutes in seconds
    const navigate = useNavigate();

    useEffect(() => {
        // Fetch questions
        axios.get(`/api/quizzes/${quizId}/questions`).then((res) => setQuestions(res.data));

        // Setup real-time listeners
        const echo = new Echo({ broadcaster: 'pusher', key: process.env.MIX_PUSHER_APP_KEY });
        echo.channel(`quiz.${quizId}`).listen('ScoreUpdated', (e) => {
            setTempScore(e.temp_score);
        });

        const countdown = setInterval(() => setTimer((prev) => prev - 1), 1000);
        return () => clearInterval(countdown);
    }, [quizId]);

    const handleSubmitAnswer = (questionId, optionId) => {
        axios.post(`/api/quizzes/${quizId}/questions/${questionId}/answer`, { option_id: optionId })
            .then((res) => {
                setTempScore(res.data.temp_score);
                setLeftQuestions(leftQuestions - 1);
                if (res.data.correct) setCorrectAnswers(correctAnswers + 1);
                else setIncorrectAnswers(incorrectAnswers + 1);
            });
    };

    const handleSubmitQuiz = () => {
        axios.post(`/api/quizzes/${quizId}/submit`).then(() => navigate('/quizzes'));
    };

    useEffect(() => {
        if (timer === 0) handleSubmitQuiz();  // Auto submit on timeout
    }, [timer]);

    return (
        <div>
            <h2>Quiz</h2>
            <div>Time Left: {Math.floor(timer / 60)}:{timer % 60}</div>

            <ul>
                {questions.map((question) => (
                    <li key={question.id}>
                        <p>{question.text}</p>
                        <ul>
                            {question.options.map((option) => (
                                <li key={option.id}>
                                    <button onClick={() => handleSubmitAnswer(question.id, option.id)} disabled={option.selected}>
                                        {option.text}
                                    </button>
                                </li>
                            ))}
                        </ul>
                    </li>
                ))}
            </ul>

            <div>
                <p>Total Questions: {questions.length}</p>
                <p>Correct Answers: {correctAnswers}</p>
                <p>Incorrect Answers: {incorrectAnswers}</p>
                <p>Questions Left: {leftQuestions}</p>
                <p>Temp Score: {tempScore}</p>
                <button onClick={handleSubmitQuiz}>Submit Quiz</button>
            </div>
        </div>
    );
};

export default QuizPage;
