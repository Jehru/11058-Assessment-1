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
        
        
        if($stmt->rowCount() > 0){

                echo "<thead>" . "<tr>" . "<th>" . "Task" . "</th>";
                echo "<th>" . "Due Date" . "</th>";
                echo  "<th>" . "Status" . "</th>";
                echo  "<th>" . "Priority" . "</th>";
                echo  "<th>" . "Notes" . "</th>" . "</tr>" . "</thead>";

            while($row = $stmt->fetch()){
                echo "<tr>" . "<td>" . $row["taskname"] . "</td>";
                echo "<td>" . $row["duedate"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>" . $row["priority"] . "</td>";
                echo "<td>" . $row["notes"] . "</td>" . "</tr>";

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