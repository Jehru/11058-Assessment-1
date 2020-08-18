<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Task Tracker</title>
    
    <!-- Links to CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="assets/style.css">

</head>
<body>
    <header class="main-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
        <p>
            <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>
    </header>

    <main class = "wrapper-main">
        <ul>
            <li><a href="create.php">Add a new artwork</a></li>
            <li><a href="read.php">Find an artwork</a></li>
            <li><a href="update.php">Update an artwork</a></li>
            <li><a href="delete.php">Delete an artwork</a></li>
        </ul>

    </main>


