<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Task Tracker</title>
    
    <!-- Links to CSS -->
    <link href="assets/style.css" rel="stylesheet" type="text/css">

</head>
<body>
    <header class="main-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to your Task Tracker.</h1>
        <a href ="welcome.php">Home</a>
        <p>
            <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>
    </header>