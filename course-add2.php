<?php session_start();
ini_set('display_errors', 1);?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
  <h2>Edit Course Details</h2>
<?php
   if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) {         ?>
        <form action = "course-add-action2.php" method = "post" enctype="multipart/form-data">
          <label>Course Name:</label>
          <input type = "text" name = "name"  required><br>
           <label>Category:</label>
          <select name="category_id" id="category_id">
            <option value="1" >Programming</option>
            <option value="2" >Web Development</option>
            <option value="3" >Data Science</option>
            <option value="4" >Cybersecurity</option>
            <option value="5" >Cloud Computing</option>
            <option value="6" >Networking</option>
            <option value="7" >Artificial Intelligence</option>
            <option value="8" >Graphic Design</option>
            <option value="9" >IT Support</option>
            <option value="10" >Mobile App Development</option>
          </select><br>
          <label>Course Description:</label> 
          <textarea cols="100" rows="10" name="description" required></textarea><br>
          <label>Date:</label>    
          <input type = "datetime-local" name = "date"  required><br>
          <label>Capacity:</label>
          <input type="number" min="1" max="50" name = "capacity"  required><br>
          <label>Image:</label> 
          <input type = "file" name = "courseimage"><br>
          <input class="btn btn_admin" type="submit" value="Save Course">
        </form>
        <?php
       
     
    } else { 
      //redirect if not logged in
      header("Location: index.php?msg=You must be logged in as an administrator");
    } //end if not logged in 
    ?>
    </section>
<?php include "includes/footer.php" ?>