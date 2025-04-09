<?php
session_start();
session_destroy();
header("Location: //us-east-1pxljybngb.auth.us-east-1.amazoncognito.com/logout?client_id=646365lsk8qi4qibn6qv9n8848&logout_uri=http://localhost/Attendance-manager/logout.php");
exit();
?>
