<?php
include 'database.php';
session_start();

$student_id = $_SESSION['student_id'] ?? 1;

// Delete only that student's attendance
$conn->query("DELETE FROM attendance WHERE student_id = $student_id");

// Delete only that student's subjects
$conn->query("DELETE FROM subjects WHERE student_id = $student_id");

echo "<script>alert('Semester restarted successfully! Please add new subjects.'); window.location.href='add_subject.php';</script>";
?>
