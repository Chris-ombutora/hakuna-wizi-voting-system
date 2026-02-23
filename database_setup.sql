CREATE DATABASE hakuna_wizi;
USE hakuna_wizi;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    has_voted_president TINYINT(1) DEFAULT 0,
    has_voted_vice TINYINT(1) DEFAULT 0
);

-- Positions table
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Candidates table
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position_id INT NOT NULL,
    FOREIGN KEY (position_id) REFERENCES positions(id)
);

-- Votes table
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    candidate_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id)
);

-- Insert initial positions
INSERT INTO positions (name) VALUES ('President'), ('Vice President');

-- Insert admin user (password: 'admin123' hashed)
INSERT INTO users (username, password, student_id) VALUES ('admin', '$2y$10$examplehashedpassword', 'ADMIN001');