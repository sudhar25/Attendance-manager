<?php
include 'database.php';
session_start();

// Simulated login - replace this with AWS user session
$student_id = $_SESSION['student_id'] ?? 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["attendance"])) {
    $date = date('Y-m-d');
    foreach ($_POST["attendance"] as $subject_id => $status) {
        if (!empty($status)) {
            $check = $conn->prepare("SELECT id FROM attendance WHERE student_id = ? AND subject_id = ? AND date = ?");
            $check->bind_param("iis", $student_id, $subject_id, $date);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, date, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $student_id, $subject_id, $date, $status);
                $stmt->execute();
                $stmt->close();
            }
            $check->close();
        }
    }
    $message = "Attendance marked successfully!";
}

$subjects = $conn->query("SELECT id, name FROM subjects");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
</head>
<body>
    <h2>Mark Attendance</h2>
    <?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>
    <form method="post">
        <table>
            <tr><th>Subject</th><th>Status</th></tr>
            <?php while ($row = $subjects->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>
                        <select name="attendance[<?php echo $row['id']; ?>]">
                            <option value="">-- Select --</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <br>
        <button type="submit">Submit Attendance</button>
    </form>
    <br>
    <a href="view_graph.php"><button>View Graph</button></a>
    <a href="restart_semester.php" onclick="return confirm('Are you sure? This will delete all attendance and subjects!');">
        <button style="color:red;">Restart Semester</button>
    </a>
</body>
</html>
