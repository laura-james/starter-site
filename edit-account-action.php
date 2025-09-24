<?php
session_start();
include("includes/connectdb.php");
if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { 
 if($_SERVER["REQUEST_METHOD"]=="POST"){
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $user_id = $_POST["user_id"];
    /* new */
    $selectedCategories = $_POST['categories']; // array of selected category IDs
    /* end new */
    if($password!=  $password2){
        echo "<p style='color:red;font-weight:bold'>Passwords do not match</p>";
    }else{
        if($password!=""){
            $hash = password_hash($password, PASSWORD_DEFAULT);       
            $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, password=? WHERE user_id=?");
            $stmt->bind_param("sssssi", $firstname, $lastname, $username, $email, $hash, $user_id);
        }else{
            $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?,  username=?, email=? WHERE user_id=?");
            $stmt->bind_param("ssssi", $firstname, $lastname, $username, $email, $user_id);
        }

        $stmt->execute();
        /* new category insert */
        // delete old user_categories
        $del_stmt = $conn->prepare("DELETE FROM user_categories WHERE user_id = ?");
        $del_stmt->bind_param("i", $user_id);
        $del_stmt->execute();

        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO  user_categories (user_id, category_id) VALUES (?,?)");
       
        foreach ($selectedCategories as $cat_id) {
            $stmt->bind_param("ii",$user_id,$cat_id);
            $stmt->execute();
        }
        /* end new category insert*/
        //echo "Edit user record successfully";
        header("Location: edit-account.php?msg=Your account was updated successfully");
      }//end update
    }//end if posted
 }else{
    header("Location: index.php?msg=You must be logged in first");
 }//end if not logged in
?>

