-- Create the database
CREATE DATABASE IF NOT EXISTS MentalHealthMonitoringSystem;

-- Use the database
USE MentalHealthMonitoringSystem;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'professional') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data (optional)
INSERT INTO users (username, password, role) 
VALUES 
('testuser', '$2y$10$examplehashedpassword', 'user'), -- Replace with a hashed password
('testprofessional', '$2y$10$examplehashedpassword', 'professional'); -- Replace with a hashed password