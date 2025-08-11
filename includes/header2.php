<!-- header -->
 <html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Pal - find computing and tech courses easily</title>
    <!-- Link to External Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <header>
      <div class="header-left">
        <a href="index.php" class="logolink">
            <img src="images/logo.svg" width="100" alt="Course Pal Logo">
            <span class="sitename">Course Pal</span>
        </a>
      </div>
      <div class="header-right">
          <?php if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { ?>
            <p class="welcome">Welcome <?php echo $_SESSION["username"]?></p>
          <?php }?>
          <nav>
              <ul>
                <li><a href="courses.php">All Courses</a></li>
                <?php if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) { ?>
                  <li><a href="index.php">Home</a> </li>
                  <li><a href="account.php">My Account</a></li>
                  <li><a href="logout.php">Logout</a></li>
                <?php } ?>
                <?php if (!isset($_SESSION["isLoggedIn"]) ) { ?>
                  <li><a href="index.php">Login</a> </li>
                  <li><a href="register.php">Register</a></li>
                <?php } ?>
                <?php if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) { ?>
                  <li><a href="admin.php">Admin</a></li>
                  <li><a href="reports.php">Reports</a></li>
                <?php } ?>   
              </ul>
          </nav>
      </div>
    </header>
    <div class="container">