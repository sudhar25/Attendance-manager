<?php
session_start();

include 'database.php';

if (!isset($_SESSION['user_email'])) {
    
    header("Location: http://localhost/Attendance-manager/");
    exit;
}

$email = $_SESSION['user_email'];
$name = $_SESSION['user_name'] ?? '';


$stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    
    $insert = $conn->prepare("INSERT INTO students (name, email) VALUES (?, ?)");
    $insert->bind_param("ss", $name, $email);
    $insert->execute();
    $student_id = $insert->insert_id;
    $insert->close();
} else {
    $row = $result->fetch_assoc();
    $student_id = $row['id'];
}
$stmt->close();

$stmt_check = $conn->prepare("SELECT COUNT(*) FROM subjects WHERE student_id = ?");
$stmt_check->bind_param("i", $student_id);
$stmt_check->execute();
$stmt_check->bind_result($subject_count);
$stmt_check->fetch();
$stmt_check->close();

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subject_name"])) {
    $subject_name = trim($_POST["subject_name"]);

    if ($subject_count >= 6) {
        $message = "You have already added 6 subjects!";
    } elseif (!empty($subject_name)) {
        

        $stmt = $conn->prepare("INSERT INTO subjects (student_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $student_id, $subject_name);

        if ($stmt->execute()) {
            $message = "Subject added successfully!";
            $subject_count++;
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Please enter a subject name.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Subject</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: white;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
        }

        p.instruction {
            font-size: 16px;
            margin-bottom: 20px;
            color: #444;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .dashboard-btn {
            background-color: #28a745;
            margin-top: 20px;
        }

        .dashboard-btn:hover {
            background-color: #218838;
        }

        footer {
            text-align: center;
            padding: 20px;
            margin-top: 60px;
            background-color: #f1f1f1;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Add New Subject</h2>
        <p class="instruction">Add subjects (Limit: 6)</p>

        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

        <?php if ($subject_count < 6): ?>
            <form method="post">
                <label for="subject_name">Subject Name:</label>
                <input type="text" name="subject_name" id="subject_name" required>
                <button type="submit">Add Subject</button>
            </form>
        <?php else: ?>
            <p style="color: red; font-weight: bold;">Subject limit reached.</p>
            <form action="dashboard.php">
                <button type="submit" class="dashboard-btn">Go to Dashboard</button>
            </form>
        <?php endif; ?>
    </div>

    <footer>
        @ 2025 All rights are reserved by GOOD FELLAS.
    </footer>

</body>
</html>
