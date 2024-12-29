<?php
include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/topbar.php");
 
// Get the selected course_id from the dropdown, default to BLEPT (1 in this case)
$course_ids = [
    'EELE' => 1,
    
];

// Get selected course, default to BLEPT
$selected_course = isset($_GET['course']) ? $_GET['course'] : 'EELE';
$selected_course_id = $course_ids[$selected_course];

// Fetch all leaderboard entries
$query = "SELECT er.id, u.first_name, u.last_name, er.score
          FROM exam_result AS er
          JOIN exam_user AS u ON u.id = er.user_id
          WHERE er.course_id = $selected_course_id
          ORDER BY er.score DESC"; // Adjust the ORDER BY as necessary
$result = mysqli_query($db, $query);

?>
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-7" style="margin-top: -10px;">
            <div>
                <h3 class="fw-bold mb-3"><span><?php echo $selected_course; ?> / </span>Leaderboard</h3>
            </div>
        </div>
        <div class="row" style="height: 50px;" >
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="card-category">
                            Leading the Pack: Honoring Our Top Reviewee
                        </p>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Courses
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php foreach ($course_ids as $course_name => $course_id): ?>
                                    <li><a class="dropdown-item" href="?course=<?php echo $course_name; ?>"><?php echo $course_name; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive table-hover table-sales" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Name</th>
                                            <th>Score</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                            $rank = 1; // Initialize rank counter
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<td>' . $rank++ . '</td>'; // Display rank
                                                echo '<td>' . ucfirst($row['first_name']) . ' ' . ucfirst($row['last_name']) . '</td>'; // Display name
                                                echo '<td class="text-start">' . $row['score'] . '</td>'; // Display score
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mapcontainer">
                                    <img src="../user/image/leaderboard.png"  class="w-100" style="height: 300px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include("../includes/scripts.php");
include("../includes/footer.php");
?>