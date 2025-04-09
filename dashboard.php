<?php
session_start();

if (!isset($_SESSION['id_token'])) {
  header("Location: https://us-east-1pxljybngb.auth.us-east-1.amazoncognito.com/login?client_id=646365lsk8qi4qibn6qv9n8848&response_type=code&scope=email+openid+phone&redirect_uri=http://localhost/Attendance-manager/cognito_callback.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Attendance Tracker</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: white;
    }

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #007bff;
      padding: 0.5rem 2rem; 
      color: white;
    }

    .nav-right a {
      margin-left: 20px;
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    .nav-right a:hover {
      text-decoration: underline;
    }

    
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 30px;
    }

    .row {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .box {
      background-color: #f8f9fa;
      border-radius: 20px;
      width: 200px;
      padding: 20px;
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .box:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .box img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 10px;
    }

    .box button {
      margin-top: 15px;
      background-color: #007bff;
      border: none;
      color: white;
      padding: 10px 15px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    .box button:hover {
      background-color: #0056b3;
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

  
  <div class="navbar">
    <div class="nav-left"><h3>Attendance Tracker</h3></div>
    <div class="nav-right">
      <a href="About us.html">About Us</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>


  <div style="text-align: center; margin: 30px;">
    <h2>Welcome to Attendance Tracker</h2>
    <p style="font-size: 18px;">Attendance important ha!!!!!!!</p>
  </div>

  
  <div class="container">
    <div class="row">
      <div class="box">
        <img src="image/add_subject.png" alt="Add Subject">
        <button onclick="location.href='add_subject.php'">Add Subject</button>
      </div>
      <div class="box">
        <img src="image/mark_attendance.png" alt="Mark Attendance">
        <button onclick="location.href='mark_attendance.php'">Mark Attendance</button>
      </div>
      <div class="box">
        <img src="image/view_graph.png" alt="View Graph">
        <button onclick="location.href='view_graph.php'">View Graph</button>
      </div>
      <div class="box">
        <img src="image/restart.png" alt="Restart">
        <button onclick="location.href='restart.php'">Restart</button>
      </div>
    </div>

    
    <div style="margin-top: 40px; text-align: center; font-size: 16px; max-width: 700px; margin-left: auto; margin-right: auto;">
      <p>1.Attendance is 75% compulsory.<br>
       <br> 2.If attendance is lower than 75% then you will not be allowed for Internal Assesment.<br>
        <br>3.Provide the proof if you have the valid reason to your class incharge<br>
        <br>
        <br>GUIDE:(if you are new)<br>

        <br>To begin using the Attendance Tracker, click on "Add Subject" to start creating your subjects.<br>

        <br>Then, use "Mark Attendance" daily and view your performance in the graph section.<br> 

        <br>You can restart the tracker anytime using the restart button.<br></p>
    </div>
  </div>


  <footer>
    @ 2025 All rights are reserved by GOOD FELLAS.
  </footer>

</body>
</html>
