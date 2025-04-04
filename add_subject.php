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
</head>
<body>
    <h2>Add New Subject</h2>

    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <form method="post">
        <label for="subject_name">Subject Name:</label>
        <input type="text" name="subject_name" id="subject_name">
        <button type="submit">Add Subject</button>
    </form>
</body>
</html>
