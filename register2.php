<?php session_start();?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
      <section>
        <h1>Register</h1>
        <!-- link the user registration form will appear here -->
         <form action = "register-action.php" method = "post">
            <label>First Name:</label>    <input type = "text" name = "firstname" required><br>
            <label>Last Name:</label>    <input type = "text" name = "lastname" required><br>
            <label>UserName:</label>    <input type = "text" name = "username" required><br>
            <label>Email:</label>    <input type = "email" name = "email" required><br>
            <label>Password:</label> <input type = "password" name = "password" required><br>
            <label>Repeat Password:</label> <input type = "password" name = "password2" required><br>
            <!-- check boxes to choose categories of interest -->
<div class="categories">
  <p>Please select which course categories you are interested in:</p>
  <?php
    // Loop through results and output as checkboxes
    $sql = "SELECT * FROM categories";
    $stmt = $conn->prepare($sql);        
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()){           
        $id = htmlspecialchars($row['category_id']);
        $name = htmlspecialchars($row['category_name']);            
        echo "<label>";
        echo "<input type='checkbox' name='categories[]' value='$id'> $name";
        echo "</label><br>";
    }//end while
  ?>
</div>
 <!-- end check boxes -->
            <input class = 'btn' type = "submit" value = "Register">
        </form>
      </section>
<?php include "includes/footer.php" ?>