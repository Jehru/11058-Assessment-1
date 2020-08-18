<?php 

	
    // include the config file that we created before
    require "../config.php"; 
    
    // this is called a try/catch statement 
	try {
        // FIRST: Connect to the database
        $connection = new PDO($dsn, $username, $password, $options);
		
        // SECOND: Create the SQL 
        $sql = "SELECT * FROM tasks";
        
        // THIRD: Prepare the SQL
        $statement = $connection->prepare($sql);
        $statement->execute();
        
        // FOURTH: Put it into a $result object that we can access in the page
        $result = $statement->fetchAll();

	} catch(PDOException $error) {
        // if there is an error, tell us what it is
		echo $sql . "<br>" . $error->getMessage();
	}	

?>

<?php include "templates/header.php"; ?>


<h2>Results</h2>

<!-- This is a loop, which will loop through each result in the array -->
<?php foreach($result as $row) { ?>

<p>
    ID: <?php echo $row['id']; ?>
    <br> 
    Task Name: <?php echo $row['taskname']; ?>
    <br> 
    Due Date: <?php echo $row['duedate']; ?>
    <br>
    Status: <?php echo $row['status']; ?>
    <br> 
    Assignee: <?php echo $row['assignee']; ?>
    <br>
    Priority: <?php echo $row['priority']; ?>
    <br>
    Notes: <?php echo $row['notes']; ?>
    <br>
    
    <a href='update-work.php?id=<?php echo $row['id']; ?>'>Edit</a>
</p>

<hr>
<?php }; //close the foreach
?>





<?php include "templates/footer.php"; ?>