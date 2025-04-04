<?php
include 'database.php';

session_start();
$student_id = $_SESSION['student_id'] ?? 1;


$student_id = 1; // Replace with actual student ID from login if needed

// Fetch subjects
$subjects_result = $conn->query("SELECT id, name, total_lectures FROM subjects");

$subject_names = [];
$attendance_percentages = [];

while ($subject = $subjects_result->fetch_assoc()) {
    $subject_id = $subject['id'];
    $subject_name = $subject['name'];
    $total_lectures = $subject['total_lectures'];

    // Count number of times the student was marked present for this subject
    $stmt = $conn->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND subject_id = ? AND status = 'Present'");
    $stmt->bind_param("ii", $student_id, $subject_id);
    $stmt->execute();
    $stmt->bind_result($present_count);
    $stmt->fetch();
    $stmt->close();

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
</head>
<body>
    <h2>Attendance Percentage Graph</h2>

    <canvas id="attendanceChart" width="600" height="400"></canvas>

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
                    borderWidth: 1
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
