-- Same as previous (run once)
CREATE DATABASE hakuna_wizi;
USE hakuna_wizi;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    voted_president TINYINT(1) DEFAULT 0,
    voted_vice TINYINT(1) DEFAULT 0
);

CREATE TABLE positions (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50) NOT NULL);
CREATE TABLE candidates (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, position_id INT NOT NULL);
CREATE TABLE votes (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, candidate_id INT);

INSERT INTO positions (name) VALUES ('President'), ('Vice President');
-- Add sample candidates via admin or manually
