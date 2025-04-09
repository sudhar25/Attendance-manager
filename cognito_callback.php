<?php
session_start();

$clientID = '646365lsk8qi4qibn6qv9n8848';
$clientSecret = 'l1cmvo8hb69k7k1s8gbdg127k80ect4pm4rhtj7qpjo8v85d1d0';
$redirectUri = 'http://localhost/Attendance-manager/cognito_callback.php';
$domain = 'https://us-east-1pxljybngb.auth.us-east-1.amazoncognito.com';

if (!isset($_GET['code'])) {
    die('Authorization code not found.');
}

$code = $_GET['code'];

$token_url = "$domain/oauth2/token";
$data = [
    'grant_type' => 'authorization_code',
    'client_id' => $clientID,
    'code' => $code,
    'redirect_uri' => $redirectUri
];

$headers = [
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Basic ' . base64_encode("$clientID:$clientSecret")
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode != 200) {
    echo "Token exchange failed:<br><pre>";
    print_r(json_decode($response, true));
    exit;
}

$tokens = json_decode($response, true);

// Store tokens in session
$_SESSION['id_token'] = $tokens['id_token'];
$_SESSION['access_token'] = $tokens['access_token'];

// Decode ID token to get user info
$jwt_parts = explode('.', $tokens['id_token']);
$payload = json_decode(base64_decode(strtr($jwt_parts[1], '-_', '+/')), true);
$name = $payload['name'] ?? 'Unknown';
$email = $payload['email'] ?? null;

if (!$email) {
    die('Email not found in ID token.');
}

// Save user to database
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "attendance_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Check if student already exists
$stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insert new user
    $insert = $conn->prepare("INSERT INTO students (name, email) VALUES (?, ?)");
    $insert->bind_param("ss", $name, $email);
    $insert->execute();
    $insert->close();
}

$stmt->close();
$conn->close();

$_SESSION['user_email'] = $email;
$_SESSION['user_name'] = $name;

header("Location: http://localhost/Attendance-manager/dashboard.php");
exit;
?>
