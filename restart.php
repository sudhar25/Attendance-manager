<?php
include 'database.php';
session_start();

$student_id = $_SESSION['student_id'] ?? 1;

// Delete student's attendance records using prepared statement
$stmt1 = $conn->prepare("DELETE FROM attendance WHERE student_id = ?");
$stmt1->bind_param("i", $student_id);
$stmt1->execute();
$stmt1->close();

// Delete student's subjects using prepared statement
$stmt2 = $conn->prepare("DELETE FROM subjects WHERE student_id = ?");
$stmt2->bind_param("i", $student_id);
$stmt2->execute();
$stmt2->close();

// Redirect with alert
echo "<script>
    alert('Semester restarted successfully! Please add new subjects.');
    window.location.href = 'add_subject.php';
</script>";
?>
