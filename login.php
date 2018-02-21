<?php
ob_start();
session_start();
require_once 'config.php';


if ( isset($_SESSION['user'])!="" ) {
    header("Location: profile.php");
    exit;
}

$error = false;

if( isset($_POST['submit']) ) { 

    //clean/sanitize user inputs to prevent sql injections
    $username = trim($_POST['username']);
    $username = strip_tags($username);
    $username = htmlspecialchars($username);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);

    //username validation
    if (empty($username)) {
        $error = true;
        $usernameE = "Please enter your user name.";
    } else if (strlen($username) < 3) {
        $error = true;
        $usernameE = "User name must have atleast 3 characters.";
    } 

    
    //password validation
    if(empty($password)){
        $error = true;
        $passwordE = "Password cannot be empty";
    }


    if (!$error) {

        $password1 = hash('sha256', $password); // password hashing using SHA256

        //prepared statements to store user data
        $query = $mysqli->prepare("SELECT password from users where Username=?");
        $query->bind_param("s", $username);
        $query->execute();
        $query->store_result();
        $query->bind_result($password1);
        $query->fetch();
        $num_of_rows = $query->num_rows;
        if($num_of_rows ==1 && $password1==$password)
        {
            header('Location: profile.php?username=' . $username);
        }
        else
        {
            $errMSG = "Username or Password is incorrect";
        }

    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta  charset="utf-8"/>
        <title>Login</title>
    </head>
    <body>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

             <h1>Login</h1>
            
            <?php
                if ( isset($errMSG) ) {

            ?>
            <span > <?php echo $errMSG; ?> </span> <br>
           
            <?php
                }
            ?>
            
            <label>Username: </label>
              
            <input type="username" name="username" placeholder="Enter Your Username" maxlength="40" value="<?php echo $username ?>" />
                
            <span><?php echo $usernameE; ?></span>
        
            <br>
            <label>Password: </label>
            <input type="password" name="password" placeholder="Enter your Password" maxlength="15" />
               
            <span><?php echo $passwordE; ?></span>
            <br>
             <button type="submit" name="submit">Sign In</button>
            <br><br>            
           
             <a href="index.php">Register</a>
        </form>
    </body>
</html>
<?php ob_end_flush(); ?>
