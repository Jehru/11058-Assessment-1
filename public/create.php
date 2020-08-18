<?php 

// this code will only execute after the submit button is clicked
if (isset($_POST['submit'])) {
	
    // include the config file that we created before
    require "../config.php"; 
    
    // this is called a try/catch statement 
	try {
        // FIRST: Connect to the database
        $connection = new PDO($dsn, $username, $password, $options);
		
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

	} catch(PDOException $error) {
        // if there is an error, tell us what it is
		echo $sql . "<br>" . $error->getMessage();
	}	
}
?>


<?php include "templates/header.php"; ?>

<h2>Add a Task</h2>

<?php if (isset($_POST['submit']) && $statement) { ?>
<p>Task successfully added.</p>
<?php } ?>

<!--form to collect data for each artwork-->

<form method="post">
    <label for="taskname">Task Name</label>
    <input type="text" name="taskname" id="taskname">

    <label for="duedate">Due Date</label>
    <input type="text" name="duedate" id="duedate">

    <label for="status">Status</label>
    <input type="text" name="status" id="status">

    <label for="assignee">Assignee</label>
    <input type="text" name="assignee" id="assignee">

    <label for="priority">Priority</label>
    <input type="text" name="priority" id="priority">

    <label for="notes">Notes</label>
    <input type="text" name="notes" id="notes">

    <input type="submit" name="submit" value="Submit">

</form>

<?php include "templates/footer.php"; ?>