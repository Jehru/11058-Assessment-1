<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>


<?php 

// this code will only execute after the submit button is clicked
	
    // include the config file that we created before
    require "../config.php"; 
    
    // this is called a try/catch statement 
	try {
        // FIRST: Connect to the database
        $connection = new PDO($dsn, $username, $password, $options);
		
        // SECOND: Create the SQL 
        // $sql = "SELECT * FROM tasks";
        
        // THIRD: Prepare the SQL
        // $statement = $connection->prepare($sql);
        // $statement->execute();

// --------------------------
        // Allows us to query the database and find all the items in the database
        $stmt = $connection->query('SELECT * FROM tasks');
        
// ------------------------------------
        // FOURTH: Put it into a $result object that we can access in the page
        // $result = $statement->fetchAll();

	} catch(PDOException $error) {
        // if there is an error, tell us what it is
		echo $sql . "<br>" . $error->getMessage();
	}	
?>



 


    <?php include "templates/header.php"; ?>

    <main>
        <ul>
            <li><a href="create.php">Add a new Task</a></li>
            <!-- <li><a href="read.php">Find a Task</a></li> -->
            <!-- <li><a href="update.php">Update an Task</a></li> -->
            <!-- <li><a href="delete.php">Delete an Task</a></li> -->
        </ul>

<!-- TESTING -->

<table class ="info-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Task</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Asignee</th>
            <th>Priority</th>
            <th>Notes</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
<?php
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
        <tr> 
            <td><?php echo $row["id"];?></td>
            <td><?php echo $row["taskname"];?></td>
            <td><?php echo $row["duedate"];?></td>
            <td><?php echo $row["status"];?></td>
            <td><?php echo $row["assignee"];?></td>
            <td><?php echo $row["priority"];?></td>
            <td><?php echo $row["notes"];?></td>
            <td> <a href='update-task.php?id=<?php echo $row['id']; ?>'' class="btn btn-info">Edit</td>
            <td> <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</td>
        </tr>
    
    <?php 
}
?>
</table>

<?php include "templates/footer.php"; ?>
<!-- END TESTING -->
