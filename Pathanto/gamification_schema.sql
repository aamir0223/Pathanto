-- Basic user accounts.
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SQL schema for gamification features.
-- Table for tracking point transactions.
CREATE TABLE user_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points INT NOT NULL,
    reason VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for tracking daily streaks.
CREATE TABLE user_streaks (
    user_id INT PRIMARY KEY,
    current_streak INT NOT NULL DEFAULT 0,
    last_active_date DATE
);

-- Table for recording per-question attempts.
CREATE TABLE question_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    correct TINYINT(1) NOT NULL,
    topic_id INT,
    difficulty VARCHAR(10),
    time_taken INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Question bank for quiz content.
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    choices TEXT,
    correct_answer VARCHAR(255),
    explanation TEXT,
    topic_id INT,
    difficulty VARCHAR(10),
    tags VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Comments on questions.
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    content TEXT NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Challenge invitations for head-to-head quizzes.
CREATE TABLE challenges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inviter_id INT NOT NULL,
    invitee_id INT NOT NULL,
    quiz_id INT NOT NULL,
    status ENUM('pending','accepted','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Daily quiz goals for personalization.
CREATE TABLE user_goals (
    user_id INT PRIMARY KEY,
    questions_per_day INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Badges earned by users.
CREATE TABLE user_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    badge VARCHAR(100) NOT NULL,
    awarded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

