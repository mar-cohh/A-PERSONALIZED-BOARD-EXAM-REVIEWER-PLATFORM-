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
        <h3 class="fw-bold mb-3">Electrical Engineering Licensure Examination</h3>
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
                  <div class="col-md-2" align="right" style="margin-left: -17px;">
                    <button type="button" id="addExam" class="btn btn-info" title="Add Subject"><i
                        class="fi fi-br-plus"></i></button>
                  </div>
                </div>
              </div>
            <div class="col-md-12">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="add-row" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Exam Subject</th>
                        <th>Course</th>
                        <th>Questions</th>
                        <th>Users</th>
                        <th>Action</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal fade" id="examModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form method="POST" id="examForm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                          <label for="course" class="control-label">Course</label>
                          <select name="course_id" id="course" class="form-control" required>
                              <option value="">Select</option>
                              <option value="1">EELE</option>
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="subject" class="control-label">Exam Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject"
                          required>
                      </div>
                      <div class="form-group">
                        <label for="question_limit" class="control-label">Number of Questions</label>
                        <input type="number" class="form-control" id="question_limit" name="question_limit" placeholder="Limit of Questions"
                          required>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <input type="hidden" name="id" id="id" />
                      <input type="hidden" name="action" id="action" value="" />
                      <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </form>
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
<script src="./JSFILE/manage_exams.js"></script>