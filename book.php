<?php session_start();
   if (isset($_SESSION["isLoggedIn"])  && $_SESSION["isLoggedIn"] == true) {    
      include "includes/connectdb.php";
      $course_id = $_GET["id"];
      $user_id = $_SESSION["userid"];
      $msg = ""; 
      $error = false;
      //check if course is full
      $sql = "SELECT capacity,(SELECT COUNT(bookings.booking_id) FROM `bookings` where 
      bookings.course_id = courses.course_id) as total_bookings FROM courses WHERE course_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $course_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if($result->num_rows > 0){ 
        while ($row = $result->fetch_assoc()){   
            $total_bookings = $row["total_bookings"];
            $capacity = $row["capacity"]; 
        }
      }else{
        echo "No records ".$sql." ".$course_id;
      }

      if ($total_bookings >= $capacity){
        $msg = "This course cannot be booked as it's full";
        $error = true;
      } 
      //check if user already booked    
      $sql = "SELECT * FROM bookings WHERE course_id = ? AND user_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ii", $course_id, $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if($result->num_rows > 0){ 
        $msg = $msg ."<br>Booking not successful - check if you have already booked this course!";
        $error = true;
      }

      if($error == false){  
        // insert the booking record 
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, course_id, booking_date) VALUES ( ?, ?, CURRENT_TIMESTAMP)");
        $stmt->bind_param("ii", $user_id,$course_id);
        $stmt->execute();
        $msg =  "Booking created successfully";
      }
      include "includes/header.php";
      echo "<section>";
      echo "<h2>Booking</h2>";
      echo $msg;
      echo "<br><br><a class='btn' href='account.php'>My Account</a>";
      echo "</section>";
      include "includes/footer.php";
  } else { 
      //redirect if not logged in
      header("Location: index.php?msg=Please login or register to book this course");
  } ?>

