<?php
session_start();
include("includes/connectdb.php");
 if($_SERVER["REQUEST_METHOD"]=="POST"){
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $selectedCategories = $_POST['categories']; // array of selected category IDs
    if($password != $password2){
        echo "<p style='color:red;font-weight:bold'>Passwords do not match</p>";
    }else{
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username,email, password) VALUES (?, ?, ?,  ?, ?)");
        $stmt->bind_param("sssss",$firstname,$lastname,$username,$email,$hash);
        $stmt->execute();
        /* user category insert */
        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO  user_categories (user_id, category_id) VALUES (?,?)");
        // Get the last inserted ID
        $new_user_id = $conn->insert_id;//Get the user_id of the last inserted row
        foreach ($selectedCategories as $cat_id) {
            // Loop through categories list and insert record
            $stmt->bind_param("ii",$new_user_id,$cat_id);
            $stmt->execute();
        }
        /* end user category insert */
        header("Location: index.php?msg=Account created. Please login");
    }
 }
?>