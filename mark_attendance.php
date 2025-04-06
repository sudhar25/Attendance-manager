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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 80px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        select {
            padding: 8px;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a button {
            text-decoration: none;
        }

        .message {
            color: green;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background-color: #f1f1f1;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Mark Attendance</h2>
        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
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
            <button type="submit">Submit Attendance</button>
        </form>
        <br>
        <a href="view_graph.php"><button>View Graph</button></a>
        <a href="restart.php" onclick="return confirm('Are you sure? This will delete all attendance and subjects!');">
            <button style="background-color: red;">Restart Semester</button>
        </a>
    </div>

    <footer>
    @ 2025 All rights are reserved by GOOD FELLAS.
    </footer>

</body>
</html>
