<?php session_start();?>
<?php include "includes/header2.php" ?>
    <section class="homelayout">
        <div class="text">
            <h1>Welcome to Course Pal</h1>
            <p> Unlock your future with hands-on tech training. Whether you're starting out or looking to upskill, our expert-led courses in coding, data science, cybersecurity, and cloud computing are designed to help you succeed in the digital world. Learn at your own pace, build real-world projects, and gain the confidence to thrive in today's tech-driven job market.</p>
            <p>With a diverse range of courses for all skill levels, we cover everything from web development and artificial intelligence to networking, digital marketing, and IT support. Whether you're a complete beginner, a career switcher, or a professional looking to advance, our flexible learning options mean there's something for everyone. Our curriculum evolves with the tech industry, ensuring you're always learning the most up-to-date and relevant skills.</p>
            <?php if (isset($_GET['msg'])) {
                $safeMsg = htmlspecialchars($_GET['msg']);
                echo "<h3 class='error'>".$safeMsg."</h3>";
            }?>
            <?php if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { ?>
              <h2>Welcome <?php echo $_SESSION["username"]?></h2>
              <a class = 'btn' href="logout.php">Logout</a>
            <?php  } else { ?>
            Please login to use the site
            
            <h2>Login</h2>
            <form action = "login2.php" method = "post">
              <label>Name:</label>    
              <input type = "text" name = "username" required><br>
              <label>Password:</label> 
              <input type = "password" name = "password" required><br>
              <input class = 'btn' type="submit" value="Login">
            </form>
            Or register here <a class = 'btn' href="register.php">Register</a>
        <?php }?>
        </div>
        <div class="image">
            <!-- featured courses to go here -->
        </div>
    </section>
        
    <!-- footer -->
    
    </div>
    <footer>
        <div class="left">&copy; CoursePal 2025 by Raspberry Pi Foundation</div>
        <div class="right">Contact Us: info@coursepal.com +44 01234 567 8910</div>
    </footer>
   
  </body>
</html>