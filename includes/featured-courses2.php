<?php include "includes/connectdb.php" ?>
<h3>Featured courses</h3>
<?php
// Get list  of user's preferred category ids
$user_cat_sql = "SELECT category_id FROM user_categories WHERE user_id = ?";
$stmt = $conn->prepare($user_cat_sql);
$stmt->bind_param("i",  $_SESSION["userid"]);
$stmt->execute();
$user_cats = [];
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $user_cats[] = $row['category_id'];
}
// Get list of course ids of user's booked and completed courses
$booked_sql = "SELECT course_id FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($booked_sql);
$stmt->bind_param("i",  $_SESSION["userid"]);
$stmt->execute();
$user_courses = [];
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $user_courses[] = $row['course_id'];
}

$course_sql = "
SELECT * FROM courses,categories
WHERE courses.category_id=categories.category_id AND date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
";
$result = $conn->query($course_sql);
$eligible_courses = [];
$preferred = [];
$other = [];
while ($course = $result->fetch_assoc()) {
    $course_id = $course["course_id"];
    if (in_array($course_id, $user_courses)) {
        continue; // skip as already booked
    }
    $booking_sql = "SELECT COUNT(*) AS num_bookings FROM bookings WHERE course_id = $course_id";
    $booking_result = $conn->query($booking_sql);
    $num_bookings = $booking_result->fetch_assoc()["num_bookings"];
    if ($course["capacity"] - $num_bookings > 0) {
        //only add to eligible courses if not fully booked
        //$eligible_courses[] = $course;
        if (in_array($course['category_id'], $user_cats)) {
            $course["subtitle"] = "Recommended";
            $preferred[] = $course;
        } else {
            $course["subtitle"] = "Starting soon!";
            $other[] = $course;
        }
    }
}

function compareStartDates($courseA, $courseB) {
    // Convert the start dates to timestamps (numbers)
    $dateA = strtotime($courseA['date']);
    $dateB = strtotime($courseB['date']);    
    // Subtract the dates to find out which is earlier
    // If result is negative, $courseA comes before $courseB
    // If result is positive, $courseB comes before $courseA
    return $dateA - $dateB;
}

// Sort both arrays using our comparison function
usort($preferred, 'compareStartDates');
usort($other, 'compareStartDates');

$eligible_courses = array_merge($preferred, $other);
$eligible_courses = array_slice($eligible_courses, 0, 8);

echo "<div class='course-grid'>";
foreach($eligible_courses as $row) {
    echo "<div class='course-card' >";
    echo "<a href='course.php?id=".$row["course_id"]."'>";
    echo "<img src='uploads/".$row["course_image"]."'>";
    echo "<h2>".$row["name"]."</h2>";
    echo "<h3>".$row["subtitle"]."</h3>";
    echo "<h6>".$row["category_name"]."</h6>";
    echo "<p>".$row["description"]."</p>";
    // Format to long date
    $date = new DateTime($row["date"]);    
    $longDate = $date->format('D j F Y H:i');
    echo "<p> Date: ".$longDate."</p>";
    echo "</a>";
    echo "</div>";
}//end while
?>