<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>


<!-- Sets all the required files and checks to UPDATES the information in the forms -->
<?php 	
    // include the config file that logs in to the database and creates a PDO instance
    require "../config.php"; 
    
    //Setting a variable called newid which is based on the session id
    $newid = $_SESSION['id'];
    
	try {
        // FIRST: Connect to the database
        $connection = new PDO($dsn, $username, $password, $options);

        // Allows us to query the database and find all the items in the database
        // This however only works for that users data, data input by other users will not show
        $stmt = $connection->query("SELECT * FROM tasks WHERE userid='$newid'");

        // Executing the id 
        $stmt->execute(['id' => $_SESSION['id']]);


        /* Riley code 
        $sql = "SELECT * FROM tasks WHERE userid=" . $_SESSION['id'];
        
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        */

	} catch(PDOException $error) {
        // if there is an error, tell us what it is
		echo $stmt . "<br>" . $error->getMessage();
	}	
?>


<!-- Creating New Data  -->

<?php 

// If user posts new information using submit
if (isset($_POST['submit'])) {
	
    // this is called a try/catch statement 
	try {
		
        // SECOND: Get the contents of the form and store it in an array
        $new_task = array( 
            "userid" => $_SESSION["id"],
            // 
            // "taskid" => $_POST["taskid"],
            "taskname" => $_POST['taskname'], 
            "duedate" => $_POST['duedate'],
            "status" => $_POST['status'],
            // "assignee" => $_POST['assignee'], 
            "priority" => $_POST['priority'], 
            "notes" => $_POST['notes'], 
        );
        
        // THIRD: Turn the array into a SQL statement
        $sql = "INSERT INTO tasks (userid, taskname, duedate, status, priority, notes) VALUES (:userid, :taskname, :duedate, :status, :priority, :notes)";        
        
        // FOURTH: Now write the SQL to the database
        $statement = $connection->prepare($sql);
        $statement->execute($new_task);

        // Updates the table with the new data
        // $stmt = $connection->query('SELECT * FROM tasks, users WHERE tasks.:userid=users.:id');
        // $stmt = $connection->query("SELECT * FROM tasks WHERE userid=:id");
        $stmt = $connection->query("SELECT * FROM tasks WHERE userid='$newid'");



	} catch(PDOException $error) {
        // if there is an error, tell us what it is
		echo $sql . "<br>" . $error->getMessage();
	}	
}

?>



<?php include "templates/header.php"; ?>

<main>
    
<!-- <a href="create.php" class="btn btn-success">Add New</a> -->

<!-- Adding In data -->
<form method="post" class="welcome-forms">
    <label for="taskname">Task Name</label>
    <input class="welcome-input" type="text" name="taskname" id="taskname">

    <label for="duedate">Due Date</label>
    <input class="welcome-input" type="date" name="duedate" id="duedate">

    <br>

    <label for="status">Status</label>
    <input class="welcome-input" type="text" name="status" id="status">

    <label for="priority">Priority</label>
    <!-- <input class="welcome-input" type="text" name="priority" id="priority"> -->
        <select class="welcome-input" name="priority" id="priority">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High🔥</option>
        </select>

    <br>

    <label for="notes">Notes</label>
    <input class="welcome-input" type="text" name="notes" id="notes">

    <br>

    <input class="create-update-submit" type="submit" name="submit" value="Submit">

</form>


<?php if (isset($_POST['submit']) && $statement) { ?>
<p>Task successfully added.</p>
<?php }?>

<h3> Your Tasks </h3>

<!-- TABLE -->
<table class ="info-table">
    <thead>
        <tr>
            <th>User ID</th>
            <th>Task ID</th>
            <th>Task</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Notes</th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
<?php
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    ?>
        <tr> 
            <td><?php echo $row["userid"];?></td>
            <td><?php echo $row["taskid"];?></td>
            <td><?php echo $row["taskname"];?></td>
            <td><?php echo $row["duedate"];?></td>
            <td><?php echo $row["status"];?></td>
            <td><?php echo $row["priority"];?></td>
            <td><?php echo $row["notes"];?></td>
            <td> <a href='update-task.php?taskid=<?php echo $row['taskid']; ?>' class="btn btn-info">Edit</td>
            <td> <a href="delete.php?taskid=<?php echo $row['taskid']; ?>" class="btn btn-danger">Delete</td>
        </tr>
    
    <?php 
}
?>
</table>

<?php include "templates/footer.php"; ?>

