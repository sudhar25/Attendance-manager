<?php
// Include your database connection
include 'database.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subject_name"])) {
    $subject_name = trim($_POST["subject_name"]);

    if (!empty($subject_name)) {
        $stmt = $conn->prepare("INSERT INTO subjects (name) VALUES (?)");
        $stmt->bind_param("s", $subject_name);

        if ($stmt->execute()) {
            $message = "Subject added successfully!";
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
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
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
        <p class="instruction">Add subjects</p>

        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

        <form method="post">
            <label for="subject_name">Subject Name:</label>
            <input type="text" name="subject_name" id="subject_name">
            <button type="submit">Add Subject</button>
        </form>
    </div>

    <footer>
    @ 2025 All rights are reserved by GOOD FELLAS.
    </footer>

</body>
</html>
