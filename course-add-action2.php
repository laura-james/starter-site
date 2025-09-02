<?php session_start();?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
    <section>
    <h2>Add Course</h2>
    <?php
    if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) { 
      if($_SERVER["REQUEST_METHOD"]=="POST"){
        $name = $_POST["name"];
        $category_id= $_POST["category_id"];   
        $description = $_POST["description"];
        $date = $_POST["date"];
        $capacity = $_POST["capacity"];        
        $stmt = $conn->prepare("INSERT INTO courses (name, description, date, capacity, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $name,$description,$date,$capacity,$category_id);
        if (!$stmt->execute()) {
            echo("Error description: " . $conn -> error);
            die();
        }
        // check if user uploaded an image....
        if($_FILES["courseimage"]["name"]!=""){
          //uploading file code - credit W3Schools
          $target_dir = "uploads/";
          $target_file = $target_dir . basename($_FILES["courseimage"]["name"]);
          $uploadOk = 1;
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

          // Check if file already exists
          if (file_exists($target_file)) {
            echo "Sorry, file already exists.<br>";
            $uploadOk = 0;
          }
          // Check file size
          if ($_FILES["courseimage"]["size"] > 5000000) {
            echo "Sorry, your file is too large.<br>";
            $uploadOk = 0;
          }
          // Allow certain file formats
          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
          }
          // Check if $uploadOk is set to 0 by an error
          if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";
          // if everything is ok, try to upload file
          } else {
            if (move_uploaded_file($_FILES["courseimage"]["tmp_name"], $target_file)) {
              echo "The file ". htmlspecialchars( basename( $_FILES["courseimage"]["name"])). " has been uploaded.";
              // Get the last inserted ID
              $new_course_id = $conn->insert_id;
              // update image file name in course record database
              $stmt = $conn->prepare("UPDATE courses SET course_image = ?  WHERE course_id = ?");
              $stmt->bind_param("ss", $_FILES["courseimage"]["name"],$new_course_id);
              if (!$stmt->execute()) {
                echo("Error description: " . $conn -> error);
                die();
              }
            } else {
              echo "Sorry, there was an error uploading your file.<br>";
            }
          }
        }//end image upload
        echo "Course added <br>";
      }else{
        echo "Nothing posted <br>";
      }
    }else { 
      //redirect if not logged in
      header("Location: index.php?msg=You must be logged in as an administrator.");
  } ?>
  </section>
<?php include "includes/footer.php" ?>
