<?php session_start();?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
<?php
if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true && $_SESSION["isAdmin"] == 1) { 
  $id= $_GET["id"];
  //fetch course details
  $course_sql = "SELECT * FROM courses WHERE course_id = ?";
  $stmt = $conn->prepare($course_sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()){   
        $course_name = $row["name"];
        $course_date = new DateTime($row["date"]);            
        $long_course_date = $course_date->format('D j M Y H:i');
  }
  //end fetch course details
  $sql = "SELECT users.user_id, users.first_name, users.last_name, users.email, 
          bookings.booking_date
          FROM bookings, users
          WHERE bookings.user_id = users.user_id AND 
          bookings.course_id = ? 
          ORDER By booking_date DESC;";   
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  if (!$result) {
    echo "Error description: " . $conn -> error;
  }
  
  if($result->num_rows > 0){ 
    echo "<h2>Class list for ".$course_name." on ".$long_course_date."</h2>";
    echo "<br>Number of students: ".$result->num_rows ."<br>";
    echo "<table border=1>";
    echo "<th>User ID</th>";
    echo "<th>Name</th>";
    echo "<th>Email</th>";
    echo "<th>Booking Date</th>";
    echo "</tr>";
    while ($row = $result->fetch_assoc()){   
        echo "<tr><td >".$row["user_id"]."</td>";
        echo "<td>".$row["first_name"]." ".$row["last_name"]."</td>";
        echo "<td>".$row["email"]."</td>";          
        // Format to long date
        $date = new DateTime($row["booking_date"]);            
        $longDate = $date->format('D j M Y H:i');
        echo "<td>".$longDate."</td></tr>";
    }//end while
  echo "</table>";
  }else{
    echo "<h3>No bookings found on this course</h3>";
  }//end if
} else { 
  //redirect if not logged in
  header("Location: index.php?msg=You must be logged in as an administrator.");
} ?>
</section>
<?php include "includes/footer.php" ?>