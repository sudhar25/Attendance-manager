<?php
include 'database.php';
session_start();

// Simulated student login — replace with actual AWS login session
$student_id = $_SESSION['student_id'] ?? 1;

// Delete student-specific attendance records
$conn->query("DELETE FROM attendance WHERE student_id = $student_id");

// Delete all subjects
$conn->query("DELETE FROM subjects");

echo "<script>alert('Semester restarted successfully!'); window.location.href='mark_attendance.php';</script>";
?>
