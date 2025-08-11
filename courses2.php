<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
        <h2>Courses List</h1>
        <!-- list of courses to appear here -->
        <?php   
        $allowed_columns = ['date', 'course_id', 'name', 'description', 'category_name']; // column names for sorting
        if (!empty($_GET["order"]) && in_array($_GET["order"], $allowed_columns)) {
            $sortorder = $_GET["order"];
        } else {
            $sortorder = "date"; // default
        }
        $sql = "SELECT * FROM courses, categories WHERE courses.category_id = categories.category_id ORDER BY $sortorder";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();


        if (!$result) {
            echo "Error description: " . $conn -> error;
        }
        if($result->num_rows > 0){   
            echo "<table><tr>";
            echo "<th><a href='?order=course_id' class='sortlink'>ID</a></th>";
            echo "<th><a href='?order=category_name' class='sortlink'>Category</a></th>";
            echo "<th><a href='?order=name' class='sortlink'>Course Name</a></th>";
            echo "<th><a href='?order=description' class='sortlink'>Description</a></th>";
            echo "<th><a href='?order=date' class='sortlink'>Date</a></th>";
            echo "<th></th></tr>";
            echo "</table>";      
            echo "<div class='course-grid'>";        
            while ($row = $result->fetch_assoc()){   
                echo "<div class='course-card' >";
                echo "<a href='course2.php?id=".$row["course_id"]."'>";
                echo "<img src='uploads/".$row["course_image"]."' width='200'>";
                echo "<h2>".$row["name"]."</h2>";  
                echo "<h6>".$row["category_name"]."</h6>"; 
                echo "<p>".$row["description"]."</p>";
                // Format to long date
                $date = new DateTime($row["date"]);
                $longDate = $date->format('D j F Y H:i');
                echo "<p class='course_date'> Date: ".$longDate."</p>";
                echo "</a>";
                echo "</div>";
            }//end while
            echo "</div>";
        }//end if
        ?>

      </section>
          
<?php include "includes/footer.php" ?>