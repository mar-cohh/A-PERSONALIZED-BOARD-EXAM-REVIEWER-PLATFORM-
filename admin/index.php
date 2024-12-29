<?php
include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/topbar.php");

// Fetch the top 3 results from the exam_result table
$topResultsQuery = "
    SELECT u.first_name, u.last_name, er.score, er.total_questions
    FROM exam_result AS er
    JOIN exam_user AS u ON u.id = er.user_id
    ORDER BY er.score DESC
    LIMIT 3
";
$topResults = mysqli_query($db, $topResultsQuery);

?>
<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3" style="margin-top: -15px;">Dashboard</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-md-6" style="margin-top: -20px;">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-primary bubble-shadow-small">
                  <i class="fas fa-users"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Users</p>
                      <?php

                      $select = "SELECT * FROM exam_user WHERE is_approved = 1";
                      $result = mysqli_query($db, $select);

                      $row = mysqli_num_rows($result);

                      echo ' <h4 class="card-title">' . $row . '</h4>';
                      ?>
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-6" style="margin-top: -20px;">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-info bubble-shadow-small">
                  <i class="fi fi-sr-question-square"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Questions</p>
                  <?php

                    $select = "SELECT * FROM exam_question ";
                    $result = mysqli_query($db, $select);

                    $row = mysqli_num_rows($result);

                    echo ' <h4 class="card-title">' . $row . '</h4>';
                    ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" style="margin-top: -22px; margin-bottom: -50px;">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row card-tools-still-right">
              <a href="leaderboard.php" class="card-title">Leaderboard</a>
            </div>
            <p class="card-category">
              Leading the Pack: Honoring Our Top Reviewee
            </p>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 ">
                <div class="table-responsive table-hover table-sales">
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
                      // Rank starts from 1
                      $rank = 1;
                      while ($row = mysqli_fetch_assoc($topResults)) {
                          $fullName = ucfirst($row['first_name']) . " " . ucfirst($row['last_name']);
                          echo "<tr>
                                  <td>{$rank}</td>
                                  <td>{$fullName}</td>
                                  <td>{$row['score']}</td>
                                </tr>";
                          $rank++;
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
             <div class="col-md-6">
                <img src="../user/image/leaderboard.png" style="width: 350px; height: 200px; align-items: center;" >
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