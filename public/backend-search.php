<?php
// Initialize the session
session_start();

?>

<?php

// Include the Config information
require "../config.php";
$newid = $_SESSION['id'];

// Attempt search query execution
try{
    if(isset($_REQUEST["term"])){
        // create prepared statement
        // $sql = "SELECT * FROM tasks WHERE taskname LIKE :term";
        $sql = "SELECT * FROM tasks WHERE userid='$newid' AND taskname LIKE :term ORDER BY priorityindex";

        $stmt = $pdo_connection->prepare($sql);
        $term = $_REQUEST["term"] . '%';
        // bind parameters to statement
        $stmt->bindParam(":term", $term);
        // execute the prepared statement
        $stmt->execute();
        
        
        // if($stmt->rowCount() > 0){
        //         echo "<thead>" . "<tr>" . "<th>" . "Task" . "</th>";
        //         echo "<th>" . "Due Date" . "</th>";
        //         echo  "<th>" . "Status" . "</th>";
        //         echo  "<th>" . "Priority" . "</th>";
        //         echo  "<th>" . "Notes" . "</th>" . "</tr>" . "</thead>";

        //     while($row = $stmt->fetch()){
        //         echo "<tr>" . "<td>" . $row["taskname"] . "</td>";
        //         echo "<td>" . $row["duedate"] . "</td>";
        //         echo "<td>" . $row["status"] . "</td>";
        //         echo "<td>" . $row["priority"] . "</td>";
        //         echo "<td>" . $row["notes"] . "</td>" . "</tr>";

        //     }
// TESTING SOMETHING

        if($stmt->rowCount() > 0){
            echo "<div class='card-group'><div class='card text-white bg-primary mb-3' style='max-width: 18rem;>'
            <div class='card-header'>Search Results</div></div> ";
            
            while($row = $stmt->fetch()){
                echo "<div class='card-body'> <h5 class='card-title'>" . $row['taskname'] . "</h5>
                <p class='card-text'>" . 
                    "<b>Due Date: </b>" . $row['duedate'] . "<br>" . 
                    "<b>Status: </b>" . $row['status'] . "<br>" . 
                    "<b>Priority: </b>" . $row['priority'] ."<br>" . 
                    "<b>Notes: </b>" . $row['notes'] . ".</p></div></div>";

            }


        } else{
            echo "<p>No matches found <br> Try using the Tasks name</p>";
        }
    }  
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
 
// Close statement
unset($stmt);
 
// Close connection
unset($pdo_connection);
?>