<?php 
session_start();
$_SESSION['page'] = 'Logout';
include 'header.php'; 
?>


<?php
session_unset();
session_destroy();
session_start();
$_SESSION['page'] = 'Login';
$_SESSION['message'] = "Logged out successfully!";
header('Location: login.php');
exit;
?>

<?php include 'footer.php'; ?>