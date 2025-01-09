CREATE DATABASE video_app;

USE video_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    video_url VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    hashtags VARCHAR(255),
    uploaded_by INT,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);
