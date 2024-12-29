<?php
include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/topbar.php");
?>

<!-- Button trigger modal -->
<div class="container">
  <div class="page-inner">
    <div style="margin-bottom: -30px; margin-top: -10px;" class=" pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Examinees</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="row">
              <div class="col-md-10">
                <h3 class="panel-title"></h3>
              </div>

            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="add-row" data-subject-id="<?php echo $_GET['subject_id']; ?>
                     class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Course</th>
                    <th>Score</th>
                    <th>Total Questions</th>
                    <th>Take Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Content will be filled by DataTable -->
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
<script src="./JSFILE/take.js"></script>
