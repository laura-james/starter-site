<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
    <h2>Course Details</h1>
    <!--course details to appear here -->
    <?php   
    $id = $_GET["id"];
    $sql = "SELECT * FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
   // $sql = "SELECT * FROM courses WHERE course_id = 1";
   // $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        echo "Error description: " . $conn -> error;
    }
    if($result->num_rows > 0){         
        while ($row = $result->fetch_assoc()){   
        echo "<div class='course-card course-card-main' >";          
            echo "<img src='uploads/".$row["course_image"]."'>";
            echo "<div class='course-details'>";
            echo "<h2>".$row["name"]."</h2>";            
            echo "<h6>".$row["category_name"]."</h6>";
            echo "<p>".$row["description"]."</p>";
            // Format to long date
            $date = new DateTime($row["date"]);            
            $longDate = $date->format('D j F Y H:i');
            echo "<p> Date: ".$longDate."</p>";
            echo "</div>";
        echo "</div>";
        echo "<div class='buttons'>";
            echo "<a class='btn' href='book.php?id=".$row["course_id"]."'>Book on course</a><br>";
        echo "</div>";
        }//end while   
    }//end if
    ?>
</section>
<?php include "includes/footer.php" ?>