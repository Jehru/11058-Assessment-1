<?php
// Initialize the session
session_start();

// Include the Config information
require "../config.php";

// Create a variable for the users session number
$newid = $_SESSION['id'];

// Try search query execution
try{
    if(isset($_REQUEST["term"])){
        // Search the database for the users id and match the taskname to the term and order by the priority of tasks
        $sql = "SELECT * FROM tasks WHERE userid='$newid' AND taskname LIKE :term ORDER BY priorityindex";

        // Prepare the SQL 
        $stmt = $pdo_connection->prepare($sql);
        $term = $_REQUEST["term"] . '%';
        // Bind parameters to statement
        $stmt->bindParam(":term", $term);
        // Execute the prepared statement
        $stmt->execute();
        

        // If there are any tasks with characters that the user searches
        // Then create a search result which shows the information to the user
        if($stmt->rowCount() > 0){
            echo "<div class='card-group'><div class='card text-white bg-primary mb-3' style='max-width: 18rem;>'<div class='card-header'>Search Results: </div></div> ";
            
            while($row = $stmt->fetch()){
                echo "<div class='card-body'> <h5 class='card-title'>" . $row['taskname'] . 
                    "</h5> <p class='card-text'>" . 
                    "<b>Due Date: </b>" . $row['duedate'] . "<br>" . 
                    "<b>Status: </b>" . $row['status'] . "<br>" . 
                    "<b>Priority: </b>" . $row['priority'] ."<br>" . 
                    "<b>Notes: </b>" . $row['notes'] . ".</p></div></div>";
            }


        } else{
            // No tasks with the input where found
            echo "<p>No matches found <br> Try using the Tasks name</p>";
        }
    }  
} catch(PDOException $e){
    // If there is an error tell us what it is
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
 
// Close statement
unset($stmt);
 
// Close connection
unset($pdo_connection);
?>