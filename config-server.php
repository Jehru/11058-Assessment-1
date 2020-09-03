<?php

// Database config 

$host       = "localhost";
$username   = "umjxqgf6yu6q5";
$password   = "u5q84q8jdjjv";
$dbname     = "dbap55a4enaz3b";

// Set DSN datasourse name, set host and database 

$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );


// Attempt to connect to MySQL database 
// Create PDO instance
// Also catch any errors if any

try{
  $pdo_connection = new PDO($dsn, $username, $password, $options);
} catch(PDOException $e){
  die("ERROR: Could not connect. " . $e->getMessage());
}

?>