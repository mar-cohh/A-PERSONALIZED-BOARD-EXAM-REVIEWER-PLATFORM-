<?php
include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/topbar.php");
 
?>
<!-- Button trigger modal -->
<div class="container">
  <div class="page-inner">
    <div style="margin-bottom: -30px; margin-top: -10px;" class="pt-2 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-3">Manage Exam Questions</h3>
        </div>
        <!-- Directly show the import form without needing the button -->
        <div class="pt-2 pb-4" id="importFrm" style="display: block;"> <!-- Display form directly -->
            <form id="csvImportForm" enctype="multipart/form-data">
                <label ><div id="fileNameDisplay"></div></label>
                <label for="csvFileInput" class="btn btn-primary">Choose CSV File</label>
                <input type="hidden" name="subject_id" value="<?php echo $_GET['subject_id']; ?>">
                <input type="file" id="csvFileInput" name="csv_file" accept=".csv" style="display:none;" required>
                <button type="submit" id="importCSV" class="btn btn-success">Import CSV</button>
            </form>
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
              <div class="col-md-2 d-flex justify-content-end">
                <button type="button" id="addQuestions" class="btn btn-info" title="Add Questions">
                  <i class="fi fi-br-plus"></i>
                </button>
                <a href="download.php?subject_id=<?php echo $_GET['subject_id']; ?>" class="btn btn-success ms-2" title=" Download CSV Template">
                  <i class="fi fi-br-download"></i>
                </a>
              </div>
                
            </div>
          </div>
          <div class="col-md-12">
            <div class="card-body">
              <div class="table-responsive">
                <table id="add-row" data-subject-id="<?php echo $_GET['subject_id']; ?>"
                  class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Question</th>
                      <th>Right Option</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <!-- Table body will go here -->
                </table>
              </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="questionsModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <form method="post" id="questionsForm">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title"></i> Edit Questions</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body"> 


                    <div class="mb-3 row">
                      <label class="col-md-4 col-form-label text-end">Question Title <span
                          class="text-danger">*</span></label>
                      <div class="col-md-8">
                        <input type="text" name="question_title" id="question_title" autocomplete="off"
                          class="form-control" />
                      </div>
                    </div>

                    <div class="mb-3 row">
                      <label for="option_title_1" class="col-md-4 col-form-label text-end">Option 1 <span
                          class="text-danger">*</span></label>
                      <div class="col-md-8">
                        <input type="text" name="option_title_1" id="option_title_1" autocomplete="off"
                          class="form-control" />
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label for="option_title_2" class="col-md-4 col-form-label text-end">Option 2 <span
                          class="text-danger">*</span></label>
                      <div class="col-md-8">
                        <input type="text" name="option_title_2" id="option_title_2" autocomplete="off"
                          class="form-control" />
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label for="option_title_3" class="col-md-4 col-form-label text-end">Option 3 <span
                          class="text-danger">*</span></label>
                      <div class="col-md-8">
                        <input type="text" name="option_title_3" id="option_title_3" autocomplete="off"
                          class="form-control" />
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label for="option_title_4" class="col-md-4 col-form-label text-end">Option 4 <span
                          class="text-danger">*</span></label>
                      <div class="col-md-8">
                        <input type="text" name="option_title_4" id="option_title_4" autocomplete="off"
                          class="form-control" />
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label for="answer_option" class="col-md-4 col-form-label text-end">Answer <span
                          class="text-danger">*</span></label>
                      <div class="col-md-8">
                        <select name="answer_option" id="answer_option" class="form-select">
                          <option value="">Select</option>
                          <option value="1">1 Option</option>
                          <option value="2">2 Option</option>
                          <option value="3">3 Option</option>
                          <option value="4">4 Option</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <input type="hidden" name="id" id="id" />
                    <input type="hidden" name="subject_id" id="subject_id" />
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
<script src="./JSFILE/questions.js"></script>

<script>
    // JavaScript to display the selected file name
    document.getElementById('csvFileInput').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : ''; // Get the selected file's name
        var fileNameDisplay = document.getElementById('fileNameDisplay');

        if (fileName) {
            fileNameDisplay.textContent = 'Selected File: ' + fileName;
        } else {
            fileNameDisplay.textContent = ''; // Clear text if no file is selected
        }
    });
</script>