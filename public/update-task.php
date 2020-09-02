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
    // Includes the config file that sets up the server
    // Also includes the common.php which adds in commonly used functions
    require "../config.php";
    require "common.php";

    $newid = $_SESSION['id'];
    $msgSuccess = '';


    // Run when submit button is clicked
    if (isset($_POST['submit'])) {
        try {
            $connection = new PDO($dsn, $username, $password, $options);  
            
            //grab elements from form and set as varaible
            $work =[
                // "userid"   => $_SESSION['id'],
                "taskid"   => $_POST['taskid'],
                "taskname" => $_POST['taskname'],
                "duedate"  => $_POST['duedate'],
                "status"   => $_POST['status'],
                "priority" => $_POST['priority'],
                "notes"    => $_POST['notes'],
                "date"     => $_POST['date'],
            ];
        
            // create SQL statement
            $sql = "UPDATE tasks
                    SET taskid = :taskid, 
                        taskname = :taskname, 
                        duedate = :duedate, 
                        status = :status, 
                        priority = :priority, 
                        notes = :notes, 
                        date = :date 
                    WHERE taskid = :taskid";

            //prepare sql statement
            $statement = $connection->prepare($sql);
            
            //execute sql statement
            $statement->execute($work);

            $msgSuccess = "<script> $(function(success) {
                alertify.set('notifier','position', 'bottom-right');
                alertify.success('Successfully Updated', 'success', 5 + alertify.get('notifier','position'));
            }); </script>";

            // sleep(6);
            // header("location: welcome.php");

        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }

    // GET data from DB
    //simple if/else statement to check if the id is available
    if (isset($_GET['taskid'])) {
        //yes the id exists 

        try {
            // standard db connection
            $connection = new PDO($dsn, $username, $password, $options);
            
            // set taskid as variable
            $taskid = $_GET['taskid'];
            
            //select statement to get the right data
            // $sql = "SELECT * FROM tasks WHERE taskid = :taskid";
            $sql = "SELECT * FROM tasks WHERE userid='$newid'";

            // prepare the connection
            $statement = $connection->prepare($sql);
            
            //bind the id to the PDO id
            $statement->bindValue(':taskid', $taskid);
            
            // now execute the statement
            $statement->execute();
            
            // attach the sql statement to the new work variable so we can access it in the form
            $work = $statement->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOExcpetion $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    } else {
        // no id, show error
        echo "No Task id - something went wrong";
        //exit;
    };


?>

<?php include "templates/header.php"; ?>

<?php //if (isset($_POST['submit'])) { 
    // Sleep 1 seconds and then send to welcome page and end the if statement

    //echo "isset post submit";
//} 
?>


<main class="wrapper-update">

<h2>Edit a work</h2>

<!-- <form method="post" class="update-forms"> -->
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

    <!-- <input class="update-input" type="text" name="priority" id="priority" value="<?php //echo escape($work['priority']); ?>"> -->
    
    <div class="form-group">
        <label for="notes">Notes</label>
        <input class="form-control" type="text" name="notes" id="notes" value="<?php echo escape($work['notes']); ?>">
    </div>

    <div class="form-group">
        <label for="date">Input Date</label>
        <input class="form-control" type="text" name="date" id="date" value="<?php echo escape($work['date']); ?>">
    </div>

    <input class="update-submit" type="submit" name="submit" value="Save Changes">

    <?php if ($msgSuccess): ?>
        <p><?=$msgSuccess?></p>
    <?php endif; ?>

</form>


<?php include "templates/footer.php"; ?>