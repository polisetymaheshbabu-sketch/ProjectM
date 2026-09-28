-- ============================================================================
-- College Faculty Record Management System (Project-M)
-- Database structure and demo data
--
-- How to import:
--   phpMyAdmin -> Import tab -> choose this file -> Go
--   (the script creates the database itself, so no database needs selecting)
--
-- This file can be imported more than once without errors: tables are only
-- created if missing, and demo rows are skipped if they already exist.
--
-- DEMO ACCOUNTS (change or delete these before any real use):
--   Admin      admin     / admin123
--   Sub-Admin  bharath      / bharath123
--   Sub-Admin  sarah     / sarah123
--   Faculty    rajesh    / rajesh123
--   Faculty    priya     / priya123
--   Faculty    amit      / amit123
--   Faculty    sunita    / sunita123
-- ============================================================================

CREATE DATABASE IF NOT EXISTS college_management
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE college_management;

-- ----------------------------------------------------------------------------
-- Admins
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------------------------
-- Sub-Admins
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS subadmins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile VARCHAR(50) NOT NULL,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------------------------
-- Colleges
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS colleges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    college_name VARCHAR(200) NOT NULL,
    college_code VARCHAR(20) NOT NULL UNIQUE,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------------------------
-- Faculty (includes optional portal login: username + password)
-- ----------------------------------------------------------------------------
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
    INDEX idx_faculty_college (college_id),
    INDEX idx_faculty_name (name),
    FOREIGN KEY (college_id) REFERENCES colleges(id) ON DELETE SET NULL
);

-- ----------------------------------------------------------------------------
-- Pages (editable About Us / Contact Us content)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(50) NOT NULL UNIQUE,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------------------------
-- Contact form messages
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contact_messages_date (created_at)
);

-- ============================================================================
-- DEMO DATA
-- ============================================================================

-- Admin
INSERT IGNORE INTO admins (id, username, password, email) VALUES
(1, 'admin', 'admin123', 'admin@collegemanagement.com');

-- Sub-Admins
INSERT IGNORE INTO subadmins (id, name, email, username, password, profile) VALUES
(1, 'Bharath', 'bharath@collegemanagement.com',  'bharath',  'bharath123',  'Sub-Admin'),
(2, 'Sarah Johnson', 'sarah@collegemanagement.com', 'sarah', 'sarah123', 'College Admin');

-- Colleges
INSERT IGNORE INTO colleges (id, college_name, college_code) VALUES
(1, 'National College of Engineering', 'NCE001'),
(2, 'City College of Arts', 'CCA002'),
(3, 'Regional Institute of Technology', 'RIT003');

-- Faculty
INSERT IGNORE INTO faculty
    (id, name, username, password, college_id, gender, designation, qualifications, phone, email, joining_date, address)
VALUES
(1, 'Dr. Rajesh Kumar', 'rajesh', 'rajesh123', 1, 'Male',   'Professor',           'Ph.D. Computer Science',  '+91 9876543220', 'rajesh@nce.edu', '2020-01-15', '123 Resident Area, Bangalore'),
(2, 'Dr. Priya Sharma', 'priya',  'priya123',  1, 'Female', 'Associate Professor', 'Ph.D. Mathematics',       '+91 9876543221', 'priya@nce.edu',  '2021-03-20', NULL),
(3, 'Prof. Amit Patel', 'amit',   'amit123',   2, 'Male',   'Assistant Professor', 'M.Tech Civil Engineering', '+91 9876543222', 'amit@cca.edu',   '2022-06-10', NULL),
(4, 'Dr. Sunita Reddy', 'sunita', 'sunita123', 3, 'Female', 'Professor',           'Ph.D. Electronics',       '+91 9876543223', 'sunita@rit.edu', '2019-09-05', NULL);

-- Default page content
INSERT IGNORE INTO pages (page_name, content) VALUES
('about', 'Welcome to our College Management System - a cutting-edge platform designed to revolutionize how educational institutions manage their faculty, colleges, and administrative operations. Our mission is to provide a seamless, efficient, and user-friendly system that connects educational institutions, faculty members, and administrators in one unified platform.'),
('contact', 'Contact Information:\n\nAddress: 123 Education Street, Knowledge City, State - 123456\nSupport Phone: +91 98765 43210\nSupport Email: support@collegemanagement.com\n\nWorking Hours:\nMonday - Friday: 9:00 AM - 6:00 PM\nSaturday: 9:00 AM - 1:00 PM');
