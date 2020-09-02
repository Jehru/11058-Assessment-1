<?php
// Starts the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php 

    // include the config file 
    require "../config.php";
    require "common.php";

    // This code will only run if the delete  is clicked
    if (isset($_GET["taskid"])) {
	    // this is called a try/catch statement 
        try {
            // define database connection
            $connection = new PDO($dsn, $username, $password, $options);
            
            // set id variable
            $taskid = $_GET["taskid"];
            
            // Create the SQL 
            $sql = "DELETE FROM tasks WHERE taskid = :taskid";

            // Prepare the SQL
            $statement = $connection->prepare($sql);
            
            // bind the id to the PDO
            $statement->bindValue(':taskid', $taskid);
            
            // execute the statement
            $statement->execute();

            // Success message
            $success = "Work successfully deleted";

        } catch(PDOException $error) {
            // if there is an error, tell us what it is
            echo $sql . "<br>" . $error->getMessage();
        }
    };

    // This code runs on page load
    try {
        $connection = new PDO($dsn, $username, $password, $options);
		
        // SECOND: Create the SQL 
        $sql = "SELECT * FROM tasks WHERE userid=" . $_SESSION['id'];
        
        // THIRD: Prepare the SQL
        $statement = $connection->prepare($sql);
        $statement->execute();
        
        // FOURTH: Put it into a $result object that we can access in the page
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

?>

<?php include "templates/header.php"; ?>


<h2>Delete a user</h2>

<?php if ($success) echo $success; ?>

<!-- This is a loop, which will loop through each result in the array -->
<?php foreach($result as $row) { ?>

<p>
    User ID: <?php echo escape($row['userid']); ?>
    <br> 
    Task ID: <?php echo escape($row['taskid']); ?>
    <br> 
    Task Name: <?php echo $row['taskname']; ?>
    <br> 
    Due Date: <?php echo $row['duedate']; ?>
    <br>
    Status: <?php echo $row['status']; ?>
    <br> 
    Priority: <?php echo $row['priority']; ?>
    <br>
    Notes: <?php echo $row['notes']; ?>
    <br>

    <a href='delete.php?taskid=<?php echo $row['taskid']; ?>'>Delete</a>
</p>

<hr>
<?php }; //close the foreach
?>



<?php include "templates/footer.php"; ?>

<!-- Returns to welcome page after the thing has been deleted -->
<!-- This one happens instantaneous as we want to simiulate it happening 
    on the welcome instead of it routing to another page  -->
<?php header("location: welcome.php"); ?>