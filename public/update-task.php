<?php
// Starts the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Includes the config file that sets up the server
// Also includes the common.php which adds in commonly used functions
require "../config.php";
require "common.php";

// Create a variable for newid and the success message. These will be used later.
$newid = $_SESSION['id'];
$msgSuccess = '';


// Updates the new information
// Run when submit button is clicked
if (isset($_POST['submit'])) {
    try {

        // Create a PDO connection which connects to the database
        $connection = new PDO($dsn, $username, $password, $options);  
        
        // Get the elements from the forms and set them as variables
        $work =[
            "taskid"   => $_POST['taskid'],
            "taskname" => $_POST['taskname'],
            "duedate"  => $_POST['duedate'],
            "status"   => $_POST['status'],
            "priority" => $_POST['priority'],
            "notes"    => $_POST['notes'],
            "date"     => $_POST['date'],
        ];
 
        // Create SQL statement. Which updates the information into tasks 
        $sql = "UPDATE tasks
                SET taskid = :taskid, 
                    taskname = :taskname, 
                    duedate = :duedate, 
                    status = :status, 
                    priority = :priority, 
                    notes = :notes, 
                    date = :date 
                WHERE taskid = :taskid";

        // Prepare the SQL statement
        $statement = $connection->prepare($sql);
        
        // Execute the SQL statement
        $statement->execute($work);

        // Create a green success message using Alertify JS 
        $msgSuccess = "<script> $(function(success) {
            alertify.set('notifier','position', 'bottom-right');
            alertify.success('Successfully Updated', 'success', 5 + alertify.get('notifier','position'));
        }); </script>";


    } catch(PDOException $error) {
        // If there is an error tell us what it is
        echo $sql . "<br>" . $error->getMessage();
    }
}


// Does this taskid exist and find the right information 
if (isset($_GET['taskid'])) {

    try {
        // Create a PDO connection which connects to the database
        $connection = new PDO($dsn, $username, $password, $options);
        
        // Set taskid as variable
        $taskid = $_GET['taskid'];
        
        // Select statement to get the right data
        $sql = "SELECT * FROM tasks WHERE taskid= :taskid";

        // Prepare the connection
        $statement = $connection->prepare($sql);
        
        // Bind the id to the PDO id
        $statement->bindValue(':taskid', $taskid);
        
        // Now execute the statement
        $statement->execute();
        
        // Attach the sql statement to the new work variable so we can access it in the form
        $work = $statement->fetch(PDO::FETCH_ASSOC);
                
    } catch(PDOExcpetion $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    // no id, show error
    echo "No Task id - something went wrong";
    exit;
}


?>

<!-- Include the header template -->
<?php include "templates/header.php"; ?>

<!-- Use the wrapper class on the main element -->
<main class="wrapper-update">

<!-- Heading -->
<h2>Edit a work</h2>

<!-- Form that show the information. This allows the user to edit the information -->
<!-- Uses bootstrap class names  -->
<form method="post">
    <div class="form-group">
        <label for="taskid">Task ID</label>
        <input class="form-control" type="text" name="taskid" id="taskid" value="<?php echo escape($work['taskid']); ?>" >
    </div>

    <div class="form-group">
        <label for="taskname">Task Name</label>
        <input class="form-control" type="text" name="taskname" id="taskname" value="<?php echo escape($work['taskname']); ?>">
    </div>

    <div class="form-group">
        <label for="duedate">Due Date</label>
        <input class="form-control" type="date" name="duedate" id="duedate" value="<?php echo ($work['duedate']); ?>">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <input class="form-control" type="text" name="status" id="status" value="<?php echo escape($work['status']); ?>">
    </div>

    <div class="form-group">
    <label for="priority">Priority</label>
        <select class="form-control" name="priority" id="priority" value="<?php echo escape($work['priority']); ?>">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High🔥</option>
        </select>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <input class="update-input" type="text" name="notes" id="notes" value="<?php echo escape($work['notes']); ?>">
    </div>

    <div class="form-group">
        <label for="date">Input Date</label>
        <input class="update-input" type="text" name="date" id="date" value="<?php echo escape($work['date']); ?>">
    </div>

    <!-- Submit button -->
    <input class="update-submit" type="submit" name="submit" value="Save Changes">

    <!--If the form has been sumbitted then send the AlertifyJS success message mentioned above-->
    <?php if ($msgSuccess): ?>
        <p><?=$msgSuccess?></p>
    <?php endif; ?>
</form>


<!-- Include the footer template -->
<?php include "templates/footer.php"; ?>