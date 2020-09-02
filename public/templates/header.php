<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Task Tracker</title>
    
    <!-- Links to CSS -->
    <link href="assets/bootstrap.min.css"  rel="stylesheet" type="text/css">
    <link href="assets/style.css" rel="stylesheet" type="text/css">

    <!-- Link to Jquery -->
    <script src="assets/jquery-min.js"></script>

    <!-- Alertify JS, Library used to send an alert -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

    <!-- Scripts -->
    <script type="text/javascript">

        // Create a function when the user types into the searchbox it searches for tasknames and shows the them in result
        $(document).ready(function(){
            $('.search-box input[type="text"]').on("keyup input", function(){
                //  Get input value on change 
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if(inputVal.length){
                    $.get("backend-search.php", {term: inputVal}).done(function(data){
                        // Display the returned data in browser
                        resultDropdown.html(data);
                    });
                } else{
                    resultDropdown.empty();
                }
            });
            
            // Set search input value on click of result item
            $(document).on("click", ".result p", function(){
                $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                $(this).parent(".result").empty();
            });
        });

    </script>
</head>

<!--  Start of HTML body -->
<body>
    <header class="main-header">
        <!-- Show the users username in the heading  -->
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to your Task Tracker.</h1>

             <!-- Give a navigation button to go home -->
            <a href ="welcome.php" id="home">Home</a>
            
            <!-- Create buttons which allow the user to reset their password and logout  -->
            <p>
                <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
                <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
            </p>
    </header>