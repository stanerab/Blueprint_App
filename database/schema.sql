CREATE DATABASE IF NOT EXISTS blueprint_app;
USE blueprint_app;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Sessions table
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ward ENUM('Hope', 'Manor', 'Lakeside') NOT NULL,
    room_number INT NOT NULL,
    initials VARCHAR(3) NOT NULL,
    datetime DATETIME NOT NULL,
    carenotes_completed TINYINT(1) DEFAULT 0,
    tracker_completed TINYINT(1) DEFAULT 0,
    notes TEXT,
    tasks_completed TINYINT(1) DEFAULT 0,
    is_archived TINYINT(1) DEFAULT 0,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Patients table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ward ENUM('Hope', 'Manor', 'Lakeside') NOT NULL,
    room_number INT NOT NULL,
    initials VARCHAR(3) NOT NULL,
    admission_date DATE,
    discharge_date DATE,
    core10_admission TINYINT(1) DEFAULT 0,
    core10_discharge TINYINT(1) DEFAULT 0,
    notes TEXT,
    is_archived TINYINT(1) DEFAULT 0,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user (password: admin123)
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO users (username, email, password_hash, full_name, role) VALUES 
('admin', 'admin@blueprint.app', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert sample data
INSERT INTO sessions (ward, room_number, initials, datetime, carenotes_completed, tracker_completed, notes, tasks_completed, created_by) VALUES
('Hope', 101, 'JD', '2024-01-15 10:30:00', 1, 1, 'Initial assessment completed', 1, 1),
('Manor', 205, 'AS', '2024-01-15 14:00:00', 1, 0, 'Follow-up session', 0, 1),
('Lakeside', 310, 'MK', '2024-01-16 09:15:00', 0, 1, 'Patient reported
-- Add patient_id to sessions table if not exists
ALTER TABLE sessions ADD COLUMN IF NOT EXISTS patient_id INT NULL AFTER id;
ALTER TABLE sessions ADD FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL;