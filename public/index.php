<?php

// Routes to index.php first, which in tern redirects the user to the login page
// Unless the user is already logged in

// Checks if the user is already logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>

