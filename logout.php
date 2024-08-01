<?php include 'header.php'; ?>


<?php
session_start();
session_unset();
session_destroy();
setcookie("user_id", "", time() - 3600, "/"); // Clear cookie
header('Location: login.php');
exit;
?>

<?php include 'footer.php'; ?>