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
$msgSuccess = '';


// READS the database

try {
    // Create a PDO connection which connects to the database
    $connection = new PDO($dsn, $username, $password, $options);

    // Find all the tasks in the database and order by prioirtyindex (high, medium low)
    // However only show the information for the user who is logged on
    $stmt = $connection->query("SELECT * FROM tasks WHERE userid='$newid' ORDER BY priorityindex");

    // Executes the id.
    // Assigns userid to sessionid
    $stmt->execute(['userid' => $_SESSION['id']]);
    // $stmt->bindParam(':userid', $newid);

} catch(PDOException $error) {
    // if there is an error, tell us what it is
    echo $stmt . "<br>" . $error->getMessage();
}	

// CREATES new data (if user clicks submit)
if (isset($_POST['submit'])) {
    
    // Try and upload the data found in the forms
    try {
        
        // Create a variable called postpriority which is a numerical value for High, Medium or Low which is used to create a hierarchy 
        $postpriority = $_POST['priority'];

        // Associates high priority with a priority index of 1, medium priority with 2 and low with 3. This is used to create an order.
        if($postpriority==="High"){
            $priorityindex = 1;
        } else if($postpriority==="Medium"){
            $priorityindex = 2;
        } else {
            $priorityindex = 3;
        };

        // Get the components of the form and store it in an Array
        $new_task = array( 
            "userid" => $_SESSION["id"],
            // Dont need Taskid as its automatically incremented
            // "taskid" => $_POST["taskid"],
            "taskname" => $_POST['taskname'], 
            "duedate" => $_POST['duedate'],
            "status" => $_POST['status'],
            "priority" => $_POST['priority'], 
            "priorityindex" => $priorityindex, 
            "notes" => $_POST['notes'], 
        );
        

        // Turn the Array into an SQL statment which is stored in SQL values
        $sql = "INSERT INTO tasks (userid, taskname, duedate, status, priority, priorityindex, notes) VALUES (:userid, :taskname, :duedate, :status, :priority, :priorityindex, :notes)";        
        
        // Write the information to the database
        $statement = $connection->prepare($sql);
        $statement->execute($new_task);

        // Create a green success message using Alertify JS 
        $msgSuccess = "<script> $(function(success) {
                    alertify.set('notifier','position', 'bottom-right');
                    alertify.success('Successfully Submitted', 'success', 5 + alertify.get('notifier','position'));
                }); </script>";

        // Sleep for 2 seconds
        sleep(2);

        // Read from the database, similar to above (This just 'updates' the form after new data has been submitted) 
        $stmt = $connection->query("SELECT * FROM tasks WHERE userid='$newid' ORDER BY priorityindex");
        
    } catch(PDOException $error) {
        // if there is an error, tell us what it is
        echo $sql . "<br>" . $error->getMessage();
    }	
}

?>


<!-- Include the header template -->
<?php include "templates/header.php"; ?>

<main>

    <!-- Forms which add data with bootstrap classes -->
    <form method="post" class="welcome-forms">

        <!-- Title for form  -->
        <h3> Add a New Task </h3>

        <!-- Each row/column/form is using bootstrap classes. A row holds two forms in one column per form -->
        <!-- Each of these forms holds the information that is going to be submitted into the database -->
        <!-- Date and priority forms have different input types -->
        <div class="row">
            <div class="col">
                <label for="taskname">Task Name</label>
                <input class="form-control" type="text" name="taskname" id="taskname">
            </div>

            <div class="col">
                <label for="duedate">Due Date</label>
                <input class="form-control" type="date" name="duedate" id="duedate">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="status">Status</label>
                <input class="form-control" type="text" name="status" id="status">
            </div>

            <div class="col">
                <label for="priority">Priority</label>
                <select class="form-control" name="priority" id="priority">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High🔥</option>
            </select>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="notes">Notes</label>
                <input class="form-control" type="text" name="notes" id="notes">
            </div>

        </div>

        <input class="create-submit" type="submit" name="submit" value="Submit" >
        
        <!--If the form has been sumbitted then send the AlertifyJS success message mentioned above-->
        <?php if ($msgSuccess): ?>
            <p><?=$msgSuccess?></p>
        <?php endif; ?>

    </form>

    <!-- Shows the users tasks -->
    <!-- This also shows todays date -->
    <h3> Your Tasks, Todays Date is <?php echo date("d/m/Y"); ?></h3>

    <!-- Search Function -->
    <!-- Hides the result unless the user starts typing into the search bar -->
    <div class="search-box">
        <input type="text" autocomplete="off" placeholder="Search for Task" />
        <div class="result"></div>
    </div>

    <!-- Shows Tasks -->
    <!-- Table which shows the users tasks  -->
    <table class="info-table">
        <thead>
            <tr>
                <th>Task</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Notes</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>

        <!-- While there is information in the database a table will present the data  -->
    <?php
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>

        <!-- This creates a new row for each row in the database -->
        <tr class="info-table-row"> 
            <td><?php echo $row["taskname"];?></td>
            <td><?php echo date('d/m/Y', strtotime($row["duedate"])); ?></td>
            <td><?php echo $row["status"];?></td>
            <td><?php echo $row["priority"];?></td>
            <td><?php echo $row["notes"];?></td>
            <td> <a href="update-task.php?taskid=<?php echo $row['taskid']; ?>" class="btn btn-info">Edit &#x270E;</td>
            <!-- Source for Edit Pencil Icon https://www.toptal.com/designers/htmlarrows/symbols/lower-right-pencil/ -->
            <td> <a href="delete.php?taskid=<?php echo $row['taskid']; ?>" class="btn btn-danger">Done &#10003;</td>
            <!-- Source for Done Tick Icon https://www.toptal.com/designers/htmlarrows/symbols/check-mark/   -->
        </tr>
    
    <?php } ?>
</table>

<!-- Include the footer template -->
<?php include "templates/footer.php"; ?>