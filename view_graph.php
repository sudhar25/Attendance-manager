<?php
include 'database.php';

session_start();
$student_id = $_SESSION['student_id'] ?? 1;

// Fetch subjects for this student
$stmt = $conn->prepare("SELECT id, name, total_lectures FROM subjects WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$subjects_result = $stmt->get_result();

$subject_names = [];
$attendance_percentages = [];

while ($subject = $subjects_result->fetch_assoc()) {
    $subject_id = $subject['id'];
    $subject_name = $subject['name'];
    $total_lectures = $subject['total_lectures'];

    // Count how many times the student marked "attended" (present)
    $stmt_attendance = $conn->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND subject_id = ? AND attended = 1");
    $stmt_attendance->bind_param("ii", $student_id, $subject_id);
    $stmt_attendance->execute();
    $stmt_attendance->bind_result($present_count);
    $stmt_attendance->fetch();
    $stmt_attendance->close();

    $percentage = ($total_lectures > 0) ? round(($present_count / $total_lectures) * 100, 2) : 0;

    $subject_names[] = $subject_name;
    $attendance_percentages[] = $percentage;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 60px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }

        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background-color: #f1f1f1;
            color: #555;
        }
        .dashboard-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.3s ease;
    
}

.dashboard-btn:hover {
    background-color: #218838;
}
.button-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;
    width: 100%;
}


    </style>
</head>
<body>
    <div class="container">
        <h2>Attendance Percentage Graph</h2>
        <canvas id="attendanceChart" width="600" height="400"></canvas>
    </div>
    <div class="button-wrapper">
    <form action="dashboard.php">
        <button type="submit" class="dashboard-btn">Go to Dashboard</button>
    </form>
</div>

    <footer>
    @ 2025 All rights are reserved by GOOD FELLAS.
    </footer>

    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($subject_names); ?>,
                datasets: [{
                    label: 'Attendance (%)',
                    data: <?php echo json_encode($attendance_percentages); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 10
                }]
            },
            options: {
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + "%";
                            }
                        },
                        title: {
                            display: true,
                            text: "Percentage"
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + "% attendance";
                            }
                        }
                    }
                }
            }
        });
    </script>
    

</body>
</html>
