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


    // Run when submit button is clicked
    if (isset($_POST['submit'])) {
        try {
            $connection = new PDO($dsn, $username, $password, $options);  
            
            //grab elements from form and set as varaible
            $work =[
              "id"         => $_POST['id'],
              "taskname" => $_POST['taskname'],
              "duedate"  => $_POST['duedate'],
              "status"   => $_POST['status'],
              "assignee"   => $_POST['assignee'],
              "priority"   => $_POST['priority'],
              "notes"   => $_POST['notes'],
              "date"   => $_POST['date'],
            ];
            
            // create SQL statement
            $sql = "UPDATE `tasks` 
                    SET id = :id, 
                        taskname = :taskname, 
                        duedate = :duedate, 
                        status = :status, 
                        assignee = :assignee, 
                        priority = :priority, 
                        notes = :notes, 
                        date = :date 
                    WHERE id = :id";

            //prepare sql statement
            $statement = $connection->prepare($sql);
            
            //execute sql statement
            $statement->execute($work);


        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }

    // GET data from DB
    //simple if/else statement to check if the id is available
    if (isset($_GET['id'])) {
        //yes the id exists 
        
        try {
            // standard db connection
            $connection = new PDO($dsn, $username, $password, $options);
            
            // set if as variable
            $id = $_GET['id'];
            
            //select statement to get the right data
            $sql = "SELECT * FROM tasks WHERE id = :id";
            
            // prepare the connection
            $statement = $connection->prepare($sql);
            
            //bind the id to the PDO id
            $statement->bindValue(':id', $id);
            
            // now execute the statement
            $statement->execute();
            
            // attach the sql statement to the new work variable so we can access it in the form
            $work = $statement->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOExcpetion $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    } else {
        // no id, show error
        echo "No id - something went wrong";
        //exit;
    };


?>

<?php include "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
	<p>Work successfully updated.</p>
<?php
    // Sleep 1 seconds and then send to welcome page and end the if statement
    sleep(1);
    header("location: welcome.php"); 
    endif; 
?>
<main>

<h2>Edit a work</h2>

<form method="post" class="create-update-forms">
    
    <label for="id">ID</label>
    <input class="create-update-input" type="text" name="id" id="id" value="<?php echo escape($work['id']); ?>" >
    
    <label for="taskname">Task Name</label>
    <input class="create-update-input" type="text" name="taskname" id="taskname" value="<?php echo escape($work['taskname']); ?>">

    <label for="duedate">Due Date</label>
    <input class="create-update-input" type="text" name="duedate" id="duedate" value="<?php echo escape($work['duedate']); ?>">

    <label for="status">Status</label>
    <input class="create-update-input" type="text" name="status" id="status" value="<?php echo escape($work['status']); ?>">

    <label for="assignee">Assignee</label>
    <input class="create-update-input" type="text" name="assignee" id="assignee" value="<?php echo escape($work['assignee']); ?>">
    
    <label for="priority">Priority</label>
    <input class="create-update-input" type="text" name="priority" id="priority" value="<?php echo escape($work['priority']); ?>">
    
    <label for="notes">Notes</label>
    <input class="create-update-input" type="text" name="notes" id="notes" value="<?php echo escape($work['notes']); ?>">
    

    <label for="date">Work Date</label>
    <input class="create-update-input" type="text" name="date" id="date" value="<?php echo escape($work['date']); ?>">

    <input class="create-update-submit" type="submit" name="submit" value="Save">

</form>


<?php include "templates/footer.php"; ?>