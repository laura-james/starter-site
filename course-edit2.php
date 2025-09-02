<?php session_start();
ini_set('display_errors', 1);?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
  <h2>Edit Course Details</h2>
<?php
   if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) { 
      $id= $_GET["id"];
      $sql = "SELECT * FROM courses WHERE course_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      if (!$result) {
        echo "Error description: " . $conn -> error;
      }//end if
      if($result->num_rows > 0){ 
        while ($row = $result->fetch_assoc()){   
            $course_id= $row["course_id"];      
            $name = $row["name"];
            $category_id= $row["category_id"];  
            $description = $row["description"];
            $date = $row["date"];
            $capacity = $row["capacity"]; 
            $course_image = $row["course_image"];
        }//end while
        ?>
        <form action = "course-edit-action2.php" method = "post" enctype="multipart/form-data">
        <input type = "hidden" name = "course_id" value="<?php echo $course_id;?>">
          <label>Course Name:</label>
          <input type = "text" name = "name" value="<?php echo $name;?>" required><br>
           <label>Category:</label>
          <select name="category_id" id="category_id">
            <option value="1" <?= ($category_id == 1) ? 'selected' : '' ?>>Programming</option>
            <option value="2" <?= ($category_id == 2) ? 'selected' : '' ?>>Web Development</option>
            <option value="3" <?= ($category_id == 3) ? 'selected' : '' ?>>Data Science</option>
            <option value="4" <?= ($category_id == 4) ? 'selected' : '' ?>>Cybersecurity</option>
            <option value="5" <?= ($category_id == 5) ? 'selected' : '' ?>>Cloud Computing</option>
            <option value="6" <?= ($category_id == 6) ? 'selected' : '' ?>>Networking</option>
            <option value="7" <?= ($category_id == 7) ? 'selected' : '' ?>>Artificial Intelligence</option>
            <option value="8" <?= ($category_id == 8) ? 'selected' : '' ?>>Graphic Design</option>
            <option value="9" <?= ($category_id == 9) ? 'selected' : '' ?>>IT Support</option>
            <option value="10" <?= ($category_id == 10) ? 'selected' : '' ?>>Mobile App Development</option>
          </select><br>
          <label>Course Description:</label> 
          <textarea cols="100" rows="10" name="description" required><?php echo $description;?></textarea><br>
          <label>Date:</label>    
          <input type = "datetime-local" name = "date" value="<?php echo $date;?>" required><br>
          <label>Capacity:</label>
          <input type="number" min="1" max="50" name = "capacity" value="<?php echo $capacity;?>" required><br>
          <img src='uploads/<?php echo $course_image;?>' width='200'><br>
          <label>Image:</label> 
          <input type = "file" name = "courseimage"><br>
          <input class="btn btn_admin" type="submit" value="Save Course">
        </form>
        <?php
       }//end if numrows > 0
     
    } else { 
      //redirect if not logged in
      header("Location: index.php?msg=You must be logged in as an administrator");
    } //end if not logged in 
    ?>
    </section>
<?php include "includes/footer.php" ?>