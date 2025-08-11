<?php session_start();?>
<?php
include("includes/connectdb.php");
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $stmt = $conn->prepare("SELECT * from users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
      echo("Error description: " . $conn -> error);
      die();
    }
    if($result->num_rows > 0){ 
        while ($row = $result->fetch_assoc()){   
            if(password_verify($_POST["password"], $row["password"])){
                // user has been authenticated
                // set session variables
                $_SESSION["isLoggedIn"] = true;
                $_SESSION["username"] = $row["first_name"] ;
                $_SESSION["userid"] = $row["user_id"] ;
                $_SESSION["isAdmin"] = $row["is_admin"] ;
                header("Location: index2.php");
            }else{
                // user found in table but wrong password
                header("Location: index2.php?msg=Incorrect password");
            }
        }
    }else{
        // username not found
        header("Location: index2.php?msg=Invalid name or password"); 
    }
}
?>