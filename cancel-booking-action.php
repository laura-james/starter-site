<?php session_start();?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
    <section>
    <h2>Booking</h2>
<?php
   if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { 
    $id= $_GET["id"];
    $userid = $_SESSION["userid"];
    $stmt = $conn->prepare("DELETE FROM `bookings` WHERE user_id= ? AND course_id = ?");
    $stmt->bind_param("ii", $userid,$id);
    if( $stmt->execute()){
      echo "Booking cancelled successfully";
    }else{
      echo "Cancellation not successful - check if you have already cancelled this course!";
    }
  } else { 
      //redirect if not logged in
      header("Location: index.php");
  } ?>
    </section>
<?php include "includes/footer.php" ?>