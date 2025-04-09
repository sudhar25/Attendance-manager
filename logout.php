<?php
session_start();
session_destroy();
header("Location: https://us-east-1pxljybngb.auth.us-east-1.amazoncognito.com/logout?client_id=646365lsk8qi4qibn6qv9n8848&logout_uri=https://us-east-1pxljybngb.auth.us-east-1.amazoncognito.com/login?client_id=646365lsk8qi4qibn6qv9n8848&response_type=code&scope=email+openid+phone&redirect_uri=http://localhost/Attendance-manager/cognito_callback.php");
exit();
?>
