<?php
session_start();

//logging out 
session_unset();
session_destroy();

//redirect to home page
header("Location: index.php");
?>