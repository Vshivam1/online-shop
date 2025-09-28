<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to login page (outside admin folder)
header("Location: ../index.php");  
exit();
?>
