-- College Management System Database Structure
-- Created for Project-M

-- Create Database
CREATE DATABASE IF NOT EXISTS college_management;
USE college_management;

-- Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: admin, password: admin123)
INSERT INTO admins (username, password, email) VALUES 
('admin', 'admin123', 'admin@collegemanagement.com');

-- Sub-Admins Table
CREATE TABLE IF NOT EXISTS subadmins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Colleges Table
CREATE TABLE IF NOT EXISTS colleges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    college_name VARCHAR(200) NOT NULL,
    college_code VARCHAR(20) NOT NULL UNIQUE,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Faculty Table (now includes login credentials)
CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    college_id INT,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    designation VARCHAR(100) NOT NULL,
    qualifications TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    joining_date DATE,
    address TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (college_id) REFERENCES colleges(id) ON DELETE SET NULL
);

-- Pages Table (for dynamic content)
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(50) NOT NULL UNIQUE,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default page content
INSERT INTO pages (page_name, content) VALUES 
('about', 'Welcome to our College Management System - a cutting-edge platform designed to revolutionize how educational institutions manage their faculty, colleges, and administrative operations. Our mission is to provide a seamless, efficient, and user-friendly system that connects educational institutions, faculty members, and administrators in one unified platform.'),
('contact', 'Contact Information:\n\nAddress: 123 Education Street, Knowledge City, State - 123456\nSupport Phone: +91 98765 43210\nSupport Email: support@collegemanagement.com\n\nWorking Hours:\nMonday - Friday: 9:00 AM - 6:00 PM\nSaturday: 9:00 AM - 1:00 PM');

-- Contact Messages Table (for contact form submissions)
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX idx_faculty_college ON faculty(college_id);
CREATE INDEX idx_faculty_name ON faculty(name);
CREATE INDEX idx_faculty_username ON faculty(username);
CREATE INDEX idx_colleges_code ON colleges(college_code);
CREATE INDEX idx_subadmins_username ON subadmins(username);
CREATE INDEX idx_contact_messages_date ON contact_messages(created_at);