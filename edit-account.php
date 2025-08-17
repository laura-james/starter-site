<?php 
include("includes/connectdb.php");
session_start();
if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { 
  // get user details from database
  $userid = $_SESSION["userid"];
  $sql = "SELECT * FROM users WHERE user_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $userid);
  $stmt->execute();
  $result = $stmt->get_result();

  if (!$result) {
    echo "Error description: " . $conn -> error;
  }
  if($result->num_rows > 0){ 
    while ($row = $result->fetch_assoc()){   
      $firstname = $row["first_name"];
      $lastname = $row["last_name"];
      $username = $row["username"];
      $email = $row["email"];
    }//endwhile
  }//endif
} else { 
  // redirect to home page if not logged in
  header("Location: index.php");
}
include "includes/header.php";?>

<section>
  <h2>Edit Account</h2>  
  <?php
      if (isset($_GET['msg'])) {
          $safeMsg = htmlspecialchars($_GET['msg']);
          echo "<h3 class='error'>".$safeMsg."</h3>";
      }
  ?>

  Please enter your details to edit your account details:
  <form action = "edit-account-action.php" method = "post">
      <input type="hidden" name="user_id" value="<?php echo $userid?>">
      <label>First Name:</label>    <input type = "text" name = "firstname" value="<?php echo $firstname?>"><br>
      <label>Last Name:</label>    <input type = "text" name = "lastname" value="<?php echo  $lastname?>"><br>
      <label>UserName:</label> <input type = "text" name = "username" value="<?php echo $username?>"><br>
      <label>Email:</label><input type = "email" name = "email" value="<?php echo $email?>"><br>
      <p>Leave password blank if you do not want to change it</p>
      <label>Password:</label> <input type = "password" name = "password"><br>
      <label>Repeat Password:</label> <input type = "password" name = "password2"><br>
      <!-- check boxes to choose categories of interest -->
      <div class="categories">
        <p>Please select which course categories you are interested in:</p>
        <?php
          // Get all categories in the database
          $sql = "SELECT * FROM categories";
          $stmt = $conn->prepare($sql);        
          $stmt->execute();
          $result = $stmt->get_result();
          
          // Get the current user's selected categories
          $userCatSql = "SELECT category_id FROM user_categories WHERE user_id = ?";
          $userCatStmt = $conn->prepare($userCatSql);
          $userCatStmt->bind_param("i", $userid);
          $userCatStmt->execute();
          $userCatResult = $userCatStmt->get_result();

          // add user categories to an array
          $user_categories = [];
          while ($uc_row = $userCatResult->fetch_assoc()) {
              $user_categories[] = $uc_row['category_id'];
          }

          // loop through categories and display a checkbox for each
          while ($row = $result->fetch_assoc()){           
              $id = htmlspecialchars($row['category_id']);
              $name = htmlspecialchars($row['category_name']);
              //set variable to "checked" if the checkbox is one of the user's chosen categories
              $checked = in_array($row['category_id'], $user_categories) ? "checked" : "";          
              echo "<label>";
              echo "<input type='checkbox' name='categories[]' value='$id' $checked> $name";
              echo "</label><br>";
          }//end while
        ?>
      </div>
      <!-- end check boxes -->
      <input type="submit" class="btn" value="Update account">
  </form>
</section>
<?php include "includes/footer.php" ?>