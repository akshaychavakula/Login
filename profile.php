<?php
    if ( isset($_GET['username'])!= ""){
        $username = $_GET['username'];
        echo "Login Successfull; Welcome " . $username;
    }
    else{
        echo "No user logged in";
        header("Location: index.php");
    }

?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <br><br>
        <div>
            <a href="logout.php">Log Out</a>
        </div>
    </body>
</html>
