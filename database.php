<?php
$host = "localhost:3307"; 
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password is empty
$database = "attendance_db"; 

// Create MySQL connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables if they don't exist
$sql_students = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
)";

$sql_subjects = "CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    total_lectures INT DEFAULT 42,
    lecture_days TEXT NOT NULL
)";

$sql_attendance = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
)";

// Execute queries
$conn->query($sql_students);
$conn->query($sql_subjects);
$conn->query($sql_attendance);

echo "Database connected and tables created successfully.";
?>
