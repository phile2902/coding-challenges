import React from 'react';
import { useNavigate } from 'react-router-dom';

const LoginPage = () => {
    const navigate = useNavigate();

    const handleSubmit = (e) => {
        e.preventDefault();
        navigate('/quizzes');  // Redirect to quiz list page after submit
    };

    return (
        <div>
            <h2>Login</h2>
            <form onSubmit={handleSubmit}>
                <label>Username: <input type="text" name="username" /></label>
                <label>Password: <input type="password" name="password" /></label>
                <button type="submit">Submit</button>
            </form>
        </div>
    );
};

export default LoginPage;
