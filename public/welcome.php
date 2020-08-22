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
    // include the config file that logs in to the database and creates a PDO intstnace
    require "../config.php"; 
    
    //
	try {
        // FIRST: Connect to the database
        $connection = new PDO($dsn, $username, $password, $options);

        // Allows us to query the database and find all the items in the database
        $stmt = $connection->query('SELECT * FROM tasks');

	} catch(PDOException $error) {
        // if there is an error, tell us what it is
		echo $stmt . "<br>" . $error->getMessage();
	}	
?>


<!-- TESTING CREATE  -->

<?php 

if (isset($_POST['submit'])) {
	

    
    // this is called a try/catch statement 
	try {
		
        // SECOND: Get the contents of the form and store it in an array
        $new_task = array( 
            "taskname" => $_POST['taskname'], 
            "duedate" => $_POST['duedate'],
            "status" => $_POST['status'],
            "assignee" => $_POST['assignee'], 
            "priority" => $_POST['priority'], 
            "notes" => $_POST['notes'], 
        );
        
        // THIRD: Turn the array into a SQL statement
        $sql = "INSERT INTO tasks (taskname, duedate, status, assignee, priority, notes) VALUES (:taskname, :duedate, :status, :assignee, :priority, :notes)";        
        
        // FOURTH: Now write the SQL to the database
        $statement = $connection->prepare($sql);
        $statement->execute($new_task);

        // Updates the table with the new data
        $stmt = $connection->query('SELECT * FROM tasks');

	} catch(PDOException $error) {
        // if there is an error, tell us what it is
		echo $sql . "<br>" . $error->getMessage();
	}	
}

?>



<?php include "templates/header.php"; ?>

<main>
    
<!-- <a href="create.php" class="btn btn-success">Add New</a> -->

    
<!-- TESTING -->
<form method="post" class="welcome-forms">
    <label for="taskname">Task Name</label>
    <input class="welcome-input" type="text" name="taskname" id="taskname">

    <label for="duedate">Due Date</label>
    <input class="welcome-input" type="text" name="duedate" id="duedate">

    <br>

    <label for="status">Status</label>
    <input class="welcome-input" type="text" name="status" id="status">

    <label for="assignee">Assignee</label>
    <input class="welcome-input" type="text" name="assignee" id="assignee">

    <br>

    <label for="priority">Priority</label>
    <input class="welcome-input" type="text" name="priority" id="priority">

    <label for="notes">Notes</label>
    <input class="welcome-input" type="text" name="notes" id="notes">

    <br>

    <input class="create-update-submit" type="submit" name="submit" value="Submit">

</form>


<?php if (isset($_POST['submit']) && $statement) { ?>
<p>Task successfully added.</p>
<?php }?>


<!-- TABLE -->
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
