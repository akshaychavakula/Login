<?php
ob_start();
session_start();
if( isset($_SESSION['user'])!="" ){
    header("Location: profile.php");
}
include_once 'config.php';

$error = false;

if ( isset($_POST['submit']) ) {

    
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
        $usernameE = "Username cannot be empty";
    } else if (strlen($username) < 3) {
        $error = true;
        $usernameE = "User name must have atleast 3 characters.";
    } 
    else{
        
        //prepared statements to store user data
        $query = $mysqli->prepare("SELECT username from users where username=?");
        $query->bind_param("s", $username);
        $query->execute();
        $query->store_result();
        $num_of_rows = $query->num_rows;
        if($num_of_rows >0 )
        {
            $error = true;
            $usernameE = "Username is already taken";
        }

    }


    //password validation
    if (empty($password)){
        $error = true;
        $passwordE = "Password cannot be empty";
    } else if(strlen($password) < 6) {
        $error = true;
        $passwordE = "Password must have atleast 6 characters.";
    }

    
    //hash password using SHA256();
    $password1 = hash('sha256', $password);

    //Continue signup process if no errors
    if( !$error ) {

        //prepared statements to store user data
        $query = $mysqli->prepare("INSERT INTO users(username,password) VALUES(?,?)");
        $query->bind_param("ss", $username,$password);
        if($query->execute())
        {
            $query->close();
            $mysqli->close();
            $errTyp = "success";
            $errMSG = "Registration Succesfull; Click Login to Login to Profile";
            unset($username);
            unset($password);
        }
        else
        {
            $errTyp = "danger";
            $errMSG = "Something went wrong; try again later"; 
        }

    }

}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset= "utf-8"/>
        <title>Register</title>
    </head>
    <body>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <h1>Register</h1>
            <?php
                if ( isset($errMSG) ) {

            ?>
            <div>
                <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
                <span ></span> <?php echo $errMSG; ?>
                </div>
            </div>
            <?php
                }
            ?>
  
            <label>Username: </label>
            <input type="username" name="username" placeholder="Enter Username" maxlength="50" value="<?php echo $username ?>" />

            <span><?php echo $usernameE; ?></span>

            <br>
            <label >Password: </label>
            <input type="password" name="password" placeholder="Enter Password" maxlength="20" />
            <span><?php echo $passwordE; ?></span>
            <br>
            <button type="submit" name="submit">Sign Up</button>
            <br><br>
            <a href="login.php">Login</a>
        </form>
    </body>
</html>
<?php ob_end_flush(); ?>
