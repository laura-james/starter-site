<?php session_start();
ini_set('display_errors', 1); ?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
    <section>
    <h2>Course Admin</h2>
    <p><a href='course-add.php' class="btn">Add New Course</a> </p>
    <br>
    <div class="searchbox">
    <form  method = "get">
          <label>Search:</label>
          <input type = "text" name = "search">          
          <input type="submit" class="btn" value="Search">
    </form>
    </div>
<?php
   if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { 
    $sql = "SELECT *,
    (SELECT COUNT(bookings.booking_id) FROM `bookings` where bookings.course_id = courses.course_id) as total 
    FROM courses,categories 
    WHERE courses.category_id = categories.category_id";
   if (!empty($_REQUEST["search"]) ){ //just search query
      $search = "%".$_REQUEST["search"]."%";
      $sql .= " AND (category_name LIKE ?";
      $sql .= " OR name LIKE ?";
      $sql .= " OR description LIKE ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $search,$search,$search);
    }else if (!empty($_REQUEST["order"])){ // just order query
      $sql .= " ORDER BY ".$_REQUEST["order"];
      //echo $sql;
      $stmt = $conn->prepare($sql);
      //$stmt->bind_param("s", $_REQUEST["order"]);
    }else{
      $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
      echo "Error description: " . $conn -> error;
    }
     echo "<br>Number of courses found: ".$result->num_rows ."<br>";
     if($result->num_rows > 0){ 
        echo "<table border=1><tr>";
        echo "<th><a href='?order=course_id'>ID</a></th>";
        echo "<th><a href='?order=category_name'>Category</a></th>";
        echo "<th> </th>";
        echo "<th><a href='?order=name'>Course Name</a></th>";
        echo "<th><a href='?order=total'>Bookings</a></th>";
        echo "<th><a href='?order=capacity'>Capacity</a></th>";
        echo "<th><a href='?order=date'>Date</a></th>";
        echo "<th width='15%'></th></tr>";
        while ($row = $result->fetch_assoc()){   
            echo "<tr><td width='2%' >".$row["course_id"]."</td>";
            echo "<td width='10%'>".$row["category_name"]."</td>";
            echo "<td width='5%'><img src='uploads/".$row["course_image"]."' width='50'></td>";
            echo "<td width='20%'>".$row["name"]."</td>";
            echo "<td width='2%'>".$row["total"]."</td>";
            echo "<td width='2%'>".$row["capacity"]."</td>";
            // Format to long date
            $date = new DateTime($row["date"]);            
            $longDate = $date->format('D j M Y H:i');
            echo "<td width='10%'>".$longDate."</td>";
            echo "<td><a class='btn' href='course.php?id=".$row["course_id"]."'>View</a>";
            echo "<a class='btn' href='course-edit.php?id=".$row["course_id"]."'>Edit</a>";
            echo "<a class='btn' href='class-list.php?id=".$row["course_id"]."'>Class List</a></td></tr>";
        }
      echo "</table>";
     }
    } else { 
      //redirect if not logged in
      header("Location: index.php");
      } ?>
    </section>
<?php include "includes/footer.php" ?>