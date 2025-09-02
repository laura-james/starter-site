<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
        <h2>Courses List</h1>
         <div class="searchbox">
            <form  method = "get">
                    <label>Search:</label>
                    <input type = "text" name = "search">          
                    <input type="submit" value="Search">
            </form>
        </div>
        <?php   
        $sql = "SELECT * FROM courses,categories 
            WHERE courses.category_id = categories.category_id";

        if (!empty($_GET["search"]) ){ //check if there is something in search
            $sql .= " AND (category_name LIKE ?";
            $sql .= " OR name LIKE ?";
            $sql .= " OR description LIKE ?)";
            }
            $allowed_columns = ['date', 'course_id', 'name', 'description', 'category_name']; // column names for sorting
            if (!empty($_GET["order"]) && in_array($_GET["order"], $allowed_columns)) {
                $sortorder = $_GET["order"];
            } else {
                $sortorder = "date"; // default
            }
            $sql .= " ORDER BY $sortorder";
            $stmt = $conn->prepare($sql);
            if (!empty($_REQUEST["search"]) ){
            $search = "%".$_GET["search"]."%";
            $stmt->bind_param("sss", $search,$search,$search);
            }
            
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