# Real-Time Vocabulary Quiz Coding Challenge

## Overview about the application

The application is a real-time vocabulary quiz that allows multiple users to join a quiz session using a unique quiz ID. As users submit answers, their scores are updated in real-time, and a leaderboard displays the current standings of all participants.
In this challenge, I will design and implement a component of the system that supports real-time quiz participation, score updates, and leaderboard updates.
I used the following technologies to implement the component:
- Laravel for the backend
- React for the frontend
- Pusher for real-time updates
- MySQL for the database
- Docker for containerization

## Acceptance Criteria

### 1. **User Participation**
- **Requirement**: Users should be able to join a quiz session using a unique quiz ID. Multiple users can join the same quiz session simultaneously.
- **API Implemented**: `GET /api/v1/quizzes/{quiz_id}/join`

  **Purpose**: This API allows a user to join a quiz session by checking if the quiz has already been joined or if the time has expired. It handles the creation or resumption of the quiz session.

  **How It Works**: The backend first checks if a user session already exists for this quiz. If found, it resumes the quiz unless the session is expired or completed. If no session exists, a new one is created and the user is provided with the quiz questions.


### 2. **Real-Time Score Updates**
- **Requirement**: Scores must update in real-time as users submit their answers.
- **API Implemented**: `POST /api/1/quizzes/{quiz_id}/questions/{question_id}/answer`

  **Purpose**: This API handles the recording of a user’s selected answer, updates the user’s temporary score for the quiz, and broadcasts the event to reflect real-time score changes.

  **How It Works**: When a user selects an option, the backend checks if the answer is correct and updates the temporary score. This score is broadcasted using Laravel Echo/Pusher for real-time updates. The final score is only committed once the quiz is fully completed.

### 3. **Real-Time Leaderboard**
- **Requirement**: Display the current standings of all participants with real-time updates.
- **API Implemented**: 
  - `GET /leaderboard/global`: Get the global leaderboard with all participants.
  - `GET /leaderboard/quiz/{quiz_id}`: Get the leaderboard for a specific quiz.
  
   **Purpose**: These APIs provide the leaderboard data for all participants or a specific quiz. The data is updated in real-time as users submit their answers.

   **How It Works**: The backend fetches the leaderboard data from the database and broadcasts it using Laravel Echo/Pusher. The frontend listens for these events and updates the leaderboard in real-time.

## Data Flow
1. **User Participation**:
   - API: `GET /api/v1/quizzes/{quiz_id}/join`
   - A user joins a quiz session by providing a unique quiz ID.
   - The backend checks if the user has already joined the quiz and resumes the session if found.
   - If no session exists, a new session is created, and the user is provided with the quiz questions.
   - Real-time event: `quiz:joined` is broadcasted to update the UI.
2. **Real-Time Score Updates**:
   - API: `POST /api/1/quizzes/{quiz_id}/questions/{question_id}/answer`
   - A user submits an answer to a quiz question.
   - The backend checks if the answer is correct and updates the user’s temporary score.
   - The updated score is broadcasted in real-time using Laravel Echo/Pusher.
   - Real-time event: `score:updated` is broadcasted to update the UI.
3. **Quiz Completion**:
   - API: `POST /api/1/quizzes/{quiz_id}/complete`
   - When the quiz is completed, the user’s temporary score is committed to their total score.
   - The leaderboard is updated with the final scores.
   - Real-time event: `quiz:completed` is broadcasted to update the UI.
4. **Real-Time Leaderboard**:
   - API: `GET /leaderboard/global` or `GET /leaderboard/quiz/{quiz_id}`
   - The leaderboard data is fetched from the database.
   - When a user submits a completed quiz, the leaderboard is updated in real-time.

## Technical Details
- **Backend**: The backend is built using Laravel 11, a PHP framework. It provides RESTful APIs for quiz participation, answer submission, and leaderboard retrieval. The backend uses MySQL as the database to store quiz data, user sessions, and scores.
- **Frontend**: The frontend is built using React 17, a JavaScript library. It provides a user interface for joining quizzes, submitting answers, and viewing the leaderboard. The frontend uses Axios to make API requests to the backend.
- **Real-Time Updates**: Real-time updates are implemented using Pusher, a hosted service that provides real-time communication between servers and clients. The backend broadcasts events using Laravel Echo, and the frontend listens for these events to update the UI in real-time.
- **Docker**: The application is containerized using Docker to ensure consistency across different environments. The backend, frontend, and database are each deployed as separate containers.
- **Scalability**: The application is designed to be scalable by using a microservices architecture. Each component can be scaled independently to handle a large number of users and quiz sessions.
- **Performance**: The application is optimized for performance by using caching, lazy loading, and asynchronous processing. The backend uses Eloquent ORM for efficient database queries, and the frontend uses React's virtual DOM for fast rendering.
- **Repository Pattern**: The backend uses the repository pattern to separate data access logic from business logic. This makes the codebase more maintainable and testable.
- **Temporary Scores**: During the quiz, user scores are tracked as “temporary” scores and only committed to the user’s total once the quiz is completed. If the user fails to complete the quiz within the time limit, their score is discarded.
- **Leaderboard**: The leaderboard displays the top scorers in real-time and updates as users submit their answers. The leaderboard can be viewed globally or for a specific quiz.
- **Error Handling**: The application handles errors gracefully by returning appropriate HTTP status codes and error messages. The frontend displays error messages to users when an API request fails.
- **Event-Driven Architecture**: The application follows an event-driven architecture where events are broadcasted from the backend to the frontend using Pusher. This allows for real-time updates without the need for polling.

## Challenge Requirements

### Part 1: System Design

1. **System Design Document**:
   - **Architecture Diagram**: Create an architecture diagram illustrating how different components of the system interact. This should include all components required for the feature, including the server, client applications, database, and any external services.
   - **Component Description**: Describe each component's role in the system.
   - **Data Flow**: Explain how data flows through the system from when a user joins a quiz to when the leaderboard is updated.
   - **Technologies and Tools**: List and justify the technologies and tools chosen for each component.

### Part 2: Implementation

1. **Pick a Component**:
   - Implement one of the core components below using the technologies that you are comfortable with. The rest of the system can be mocked using mock services or data.

2. **Requirements for the Implemented Component**:
   - **Real-time Quiz Participation**: Users should be able to join a quiz session using a unique quiz ID.
   - **Real-time Score Updates**: Users' scores should be updated in real-time as they submit answers.
   - **Real-time Leaderboard**: A leaderboard should display the current standings of all participants in real-time.

3. **Build For the Future**:
   - **Scalability**: Design and implement your component with scalability in mind. Consider how the system would handle a large number of users or quiz sessions. Discuss any trade-offs you made in your design and implementation.
   - **Performance**: Your component should perform well even under heavy load. Consider how you can optimize your code and your use of resources to ensure high performance.
   - **Reliability**: Your component should be reliable and handle errors gracefully. Consider how you can make your component resilient to failures.
   - **Maintainability**: Your code should be clean, well-organized, and easy to maintain. Consider how you can make it easy for other developers to understand and modify your code.
   - **Monitoring and Observability**: Discuss how you would monitor the performance of your component and diagnose issues. Consider how you can make your component observable.

## Submission Guidelines

Candidates are required to submit the following as part of the coding challenge:

1. **System Design Documents**:
   - **Architecture Diagram**: Illustrate the interaction of system components (server, client applications, database, etc.).
   - **Component Descriptions**: Explain the role of each component.
   - **Data Flow**: Describe how data flows from user participation to leaderboard updates.
   - **Technology Justification**: List the chosen technologies and justify why they were selected.

2. **Working Code**:
   - Choose one of the core components mentioned in the requirements and implement it using your preferred technologies. The rest of the system can be mocked using appropriate mock services or data.
   - Ensure the code meets criteria such as scalability, performance, reliability, maintainability, and observability.

3. **Video Submission**:
   - Record a short video (5-10 minutes) where you address the following:
     - **Introduction**: Introduce yourself and state your name.
     - **Assignment Overview**: Describe the technical assignment that ELSA gave in your own words. Feel free to mention any assumptions or clarifications you made.
     - **Solution Overview**: Provide a crisp overview of your solution, highlighting key design and implementation elements.
     - **Demo**: Run the code on your local machine and walk us through the output or any tests you’ve written to verify the functionality.
     - **Conclusion**: Conclude with any remarks, such as challenges faced, learnings, or further improvements you would make.

   **Video Requirements**:
   - The video must be between **5-10 minutes**. Any submission beyond 10 minutes will be rejected upfront.
   - Use any recording device (smartphone, webcam, etc.), ensuring good audio and video quality.
   - Ensure clear and concise communication.
