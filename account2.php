<?php session_start();?>
<?php include "includes/header2.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
  <h2>My Account</h2>
  <p><a class="btn" href="edit-account.php">Edit Account Details</a></p>
  <?php
  if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { 
    $userid = $_SESSION["userid"];
    $stmt = $conn->prepare("SELECT * FROM bookings,courses 
    WHERE courses.course_id=bookings.course_id AND user_id=?
    ORDER BY date ASC;");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
      echo "Error description: " . $conn -> error;
    }
      echo "You are currently booked on ".$result->num_rows ." courses.<br>";
      if($result->num_rows > 0){ 
        echo "<table border=1><tr>";
        echo "<th>Name</th>";
        echo "<th>Course Date</a></th>";
        echo "<th>Booking Date</th>";
        echo "<th></th>";
        echo "<th></th></tr>"; 
        while ($row = $result->fetch_assoc()){   
            echo "<td><strong>".$row["name"]."<strong></td>";
            echo "<td>".$row["date"]."</td>";
            echo "<td>".$row["booking_date"]."</td>";
            echo "<td><a class = 'btn' href='course.php?id=".$row["course_id"]."'>Learn more</a></td>";
            echo "<td><a class = 'btn btn_cancel' href='cancel-booking-action.php?id=".$row["course_id"]."'>Cancel booking</a></td>";
            echo "</tr>";
        }
      echo "</table>";
      }
  } else { 
    //redirect if not logged in
    header("Location: index.php?msg=You must be logged in");
  } ?>
</section>
<?php include "includes/footer.php" ?>