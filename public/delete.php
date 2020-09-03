<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include the config file that logs in to the database and creates a PDO instance
// Also includes the common.php which adds in the escape funtion
require "../config.php";
require "common.php";

// Creating a varible for the users session ID
$newid = $_SESSION['id'];
// Create an empty variable which will be used for the success messages
$msg_notifier = '';


// Deletes the information
// Run when delete button is clicked
if (isset($_GET["taskid"])) {
    try {

        // Create a PDO connection which connects to the database
        $connection = new PDO($dsn, $username, $password, $options);
        
        // Sets the taskid to a varible called $taskid
        $taskid = $_GET["taskid"];
        
        // Create SQL statement. Which deletes the information with that id 
        $sql = "DELETE FROM tasks WHERE taskid = :taskid";

        // Prepare the SQL
        $statement = $connection->prepare($sql);
        
        // Bind the id to the PDO
        $statement->bindValue(':taskid', $taskid);
        
        // Execute the statement
        $statement->execute();

        // Create a green success message using Alertify JS 
        $msg_notifier = "<script> $(function(success) {
            alertify.set('notifier','position', 'bottom-right');
            alertify.success('Successfully Updated', 'success', 5 + alertify.get('notifier','position'));
        }); </script>";

    } catch(PDOException $error) {
        // If there is an error, tell us what it is
        echo $sql . "<br>" . $error->getMessage();
    }
};

// This code runs on page load
try {
    // Create a PDO connection which connects to the database
    $connection = new PDO($dsn, $username, $password, $options);
    
    // Create the SQL which reads all the data on the database for that user
    $sql = "SELECT * FROM tasks WHERE userid='$newid'";

    // Prepare the SQL
    $statement = $connection->prepare($sql);
    $statement->execute();
    
    // Put it into a $result object that we can access in the page
    $result = $statement->fetchAll();

} catch(PDOException $error) {
    // If there is an error, tell us what it is
    echo $sql . "<br>" . $error->getMessage();
}
?>


<!-- 
    If the page routing fails (see at the bottom) to go back to the welcome page then this information is displayed 
    where the user can manually delete their information with bootstrap styled cards.   
-->

<!-- Include the header template -->
<?php include "templates/header.php"; ?>

<!-- Title for delete page -->
<h2>Delete a user</h2>

<!-- This is a loop, which will loop through each result in the array -->
<!-- Creates a little card which shows each of the elements in the database -->
<?php foreach($result as $row) { ?>

    <div class="card bg-light mb-3" style="max-width: 18rem;">
        <div class="card-header"> <?php echo($row['taskname']); ?></div>
        <div class="card-body">
            <h5 class="card-title">Due Date:</h5>
            <p class="card-text"> <?php echo($row['duedate']); ?></p>
            <h5 class="card-title">Status:</h5>
            <p class="card-text"> <?php echo($row['status']); ?></p>
            <h5 class="card-title">Priority:</h5>
            <p class="card-text"> <?php echo($row['priority']); ?></p>
            <h5 class="card-title">Notes:</h5>
            <p class="card-text"> <?php echo($row['notes']); ?></p>

            <a href='delete.php?taskid=<?php echo $row['taskid']; ?>'>Delete</a>
        </div>
    </div>


<?php };
?>


<!-- Include the footer template -->
<?php include "templates/footer.php"; ?>

<!-- Returns to welcome page after the thing has been deleted -->
<!-- This happens instantly as I want to simiulate it happening 
    on the welcome instead of it routing to another page  -->
<?php header("location: welcome.php"); ?>