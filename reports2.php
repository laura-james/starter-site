<?php session_start();?>
<?php include "includes/header.php" ?>
<?php include "includes/connectdb.php" ?>
<section>
<h2>Course Reports</h2>
<p>Shows live information of courses and the number of bookings per course.</p>
<?php
   if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true && $_SESSION["isAdmin"] == 1) { 
   //using LEFT JOIN shows the empty categories
    $sql = "SELECT 
      courses.name,
      COUNT(bookings.booking_id) AS total_bookings,
      courses.course_id,
      categories.category_colour
    FROM 
      courses
    LEFT JOIN bookings ON courses.course_id = bookings.course_id
    JOIN categories ON courses.category_id = categories.category_id
    GROUP BY courses.course_id 
    ORDER BY 
      total_bookings DESC";
   
    $stmt = $conn->prepare($sql);    
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
      echo "Error description: " . $conn -> error;
    }
     echo "Number of courses found: ".$result->num_rows ."<br>";
    if($result->num_rows > 0){   
    // create an array to hold data from the database which will be used by Javascript
    $data = [];
    while ($row = $result->fetch_assoc()) {
        /* add element to array which holds the course title, the total booking, 
        the course id, the category colour - used for the colour of the bar */
        $data[] = [$row['name'], (int)$row['total_bookings'],$row['course_id'],$row['category_colour']];
    }// end while
 }// end if
} else { 
  //redirect if not logged in
  header("Location: index.php?msg=You must be logged in as an administrator.");
}//end if logged in ?>
<div class="chart-container" style="position: relative; height:90vh; width:90vw">
  <canvas id="myChart"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
  // Draw the chart using ChartJS
  // Create Javascript array: labels
  const labels = [
  <?php
  foreach ($data as $entry) {
      echo "'" . $entry[0] . "',";
  }
  ?>
  ];
  // Create Javascript array: num_bookings
  const num_bookings = [
  <?php
  foreach ($data as $entry) {
      echo "'" . $entry[1] . "',";
  }
  ?>
  ];

  // Create Javascript array: bar_colours
  const bar_colours = [
  <?php
  foreach ($data as $entry) {
      echo "'" . $entry[3] . "',";
  }
  ?>
  ];
  // Create Javascript array: labelLinks 
  const labelLinks = [
  <?php
  foreach ($data as $entry) {
      echo "'course2.php?id=" . $entry[2] . "',";
  }
  ?>
  ];
  const data = {
    labels: labels,
    datasets: [{
        label: 'Bookings',
        data: num_bookings,
        backgroundColor: bar_colours
    }]
  };
  //ChartJS Configuration settings
  const config = {
    type: 'bar',
    data: data,
    animation: true,
    options: {
      indexAxis: 'y', // Horizontal bars
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: {
            ticks: {
            autoSkip: false,
            font: { size: 14 },
            color: 'blue'
            }
        }
      }
    }
  };
  const myChart = new Chart(document.getElementById('myChart'), config);
        // Add click event for y-axis labels
      document.getElementById('myChart').onclick = function(evt) {
        const chartArea = myChart.chartArea;
        const yAxis = myChart.scales.y;
        const mouseY = evt.offsetY;
        // Find the label index from mouseY
        const ticks = yAxis.ticks;
        for (let i = 0; i < ticks.length; i++) {
          // Get the centre pixel position for this tick
          const pixel = yAxis.getPixelForTick(i);
          if (Math.abs(mouseY - pixel) < (yAxis.height / ticks.length) / 2) {
            // label click detected so redirect to the link
            window.location.href = labelLinks[i];
            break;
          }
        }
      };
</script>
</section>
<?php include "includes/footer.php" ?>