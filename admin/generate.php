<?php
include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/topbar.php");

// Query to get user activity
$userActivityQuery = "
    SELECT ua.user_id, u.first_name, u.last_name, u.email, ua.activity_type, ua.activity_time 
    FROM user_activity AS ua 
    JOIN exam_user AS u ON ua.user_id = u.id 
    ORDER BY ua.activity_time DESC
";
$userActivityResult = mysqli_query($db, $userActivityQuery);
// Check for query errors
if (!$userActivityResult) {
    die('Query Error: ' . mysqli_error($db));
}
// Fetch user activities
$userActivity = [];
while ($row = mysqli_fetch_assoc($userActivityResult)) {
    $userActivity[] = $row;
}


// Fetch quiz results
$quizResults = [];
$quizResultsQuery = "
    SELECT er.user_id, u.first_name, u.last_name, er.score, er.created_at, c.course AS course, s.subject AS subject 
    FROM exam_result AS er
    JOIN exam_user AS u ON er.user_id = u.id 
    JOIN exam_course AS c ON er.course_id = c.id
    JOIN exam_subject AS s ON er.subject_id = s.id
";
$quizResultsResult = mysqli_query($db, $quizResultsQuery);

if ($quizResultsResult) {
    while ($row = mysqli_fetch_assoc($quizResultsResult)) {
        $quizResults[] = $row;
    }
}

// Fetch user data
$userExamResults = [];
$examResultsQuery = "
    SELECT id, first_name, last_name, email, created, is_approved
    FROM exam_user";

$examResultsResult = mysqli_query($db, $examResultsQuery);
if ($examResultsResult) {
    while ($row = mysqli_fetch_assoc($examResultsResult)) {
        $userExamResults[] = $row;
    }
}
?>

<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-7" style="margin-top: -10px;">
            <div>
                <h3 class="fw-bold mb-3">Generate Report</h3>
            </div>
        </div>
        <div class="row" style="height: auto;">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="card-category">
                            User Activity
                        </p>
                        <a href="export_pdf.php" class="btn btn-primary">Export as PDF</a> <!-- Add the button here -->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-hover table-sales" style="max-height: 300px; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Activity Type</th>
                                        <th>Activity Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($userActivity as $activity): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($activity['user_id']); ?></td>
                                        <td><?php echo htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($activity['email']); ?></td>
                                        <td><?php echo htmlspecialchars($activity['activity_type']); ?></td>
                                        <td><?php echo htmlspecialchars($activity['activity_time']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  
                <div class="card card-round">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="card-category">Quiz Results</p>
                        <a href="export_quiz_pdf.php" class="btn btn-primary">Export as PDF</a>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-hover table-sales" style="max-height: 300px; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Subject</th>
                                        <th>Score</th>
                                        <th>Date Taken</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($quizResults)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No quiz results found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($quizResults as $result): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($result['user_id']); ?></td>
                                            <td><?php echo htmlspecialchars($result['first_name'] . ' ' . $result['last_name']); ?></td>
                                            <td><?php echo htmlspecialchars($result['course']); ?></td>
                                            <td><?php echo htmlspecialchars($result['subject']); ?></td>
                                            <td><?php echo htmlspecialchars($result['score']); ?></td>
                                            <td><?php echo htmlspecialchars($result['created_at']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card card-round">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="card-category">User List</p>
                        <a href="export_user_pdf.php" class="btn btn-primary">Export as PDF</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-hover table-sales" style="max-height: 300px; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($userExamResults as $result) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($result['id']); ?></td>
                                        <td><?php echo htmlspecialchars($result['first_name'] . ' ' . $result['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($result['email']); ?></td>
                                        <td><?php echo htmlspecialchars($result['created']); ?></td>
                                        <td><?php echo htmlspecialchars($result['is_approved'] ? 'APPROVED' : 'PENDING'); ?></td> <!-- Check approval status -->
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
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
