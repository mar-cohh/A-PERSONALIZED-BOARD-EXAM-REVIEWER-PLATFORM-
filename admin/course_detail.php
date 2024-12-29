<?php

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/topbar.php");

// Get course_id from URL
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

include ("../admin/login_action_admin.php");


?>
<!-- Button trigger modal -->
<div class="container">
  <div class="page-inner">
    <div style="margin-bottom: -30px; margin-top: -10px;" class=" pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Agriculturist Licensure Examination</h3>
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
                              <option value="4">ALE</option>
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="subject" class="control-label">Exam Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject"
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

<?php
include 'config/dbconfig.php';
include_once 'class/class_ales.php';

$database = new Database();
$db = $database->getConnection();

$ales = new Ales ($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listExam') {
	$ales->listExam();
} 
 
 if(!empty($_POST['action']) && $_POST['action'] == 'getExam') {
	$ales->id = $_POST["id"];
	$ales->getExam();      
}  

if(!empty($_POST['action']) && $_POST['action'] == 'addExam') {	
	$ales->subject = $_POST["subject"];
	$ales->course_id = $_POST["course_id"];
	$ales->insert();
}


 if(!empty($_POST['action']) && $_POST['action'] == 'updateExam') {
	$ales->id = $_POST["id"];
	$ales->subject = $_POST["subject"];    
	$ales->course_id = $_POST["course_id"];
	$ales->update();
}

if (!empty($_POST['action']) && $_POST['action'] == 'deleteExam') {
	// Ensure the ID is set and is an integer
	if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
	    $ales->id = intval($_POST["id"]); // Cast to integer for safety
   
	    // Attempt to delete and check the result
	    if ($ales->delete()) {
		 echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
	    } else {
		 echo json_encode(["status" => "error", "message" => "Error deleting record!"]);
	    }
	} else {
	    echo json_encode(["status" => "error", "message" => "Invalid ID!"]);
	}
   }
?>



<script type="javascript">
  $(document).ready(function () {
	var examRecords = $("#add-row").DataTable({
		"lengthChange": false,
		"processing": true,
		"serverSide": true,
		"bFilter": true,
		"serverMethod": "post",
		"order": [],
		"ajax": {
		"url": "ales_action.php",
		"type": "POST",
		"data": { action: "listExam" },
		"dataType": "json",
		},
		"columnDefs": [
		{
			targets: [0, 3, 4, 5, 6], // Targeting ID, and action buttons columns
			orderable: false, // Disable sorting for these columns
			width: "5%", // Set a default width for these columns
		},
		{
			targets: 0, // ID column
			width: "5%",
		},
		{
			targets: 1, // Exam Subject column
			width: "10%",
		},
		{
			targets: 2, // Total Questions column
			width: "10%",
		},
		{
			targets: 3, // course
			width: "10%",
		},
		{
			targets: 4, // question
			width: "10%",
		},
		{
			targets: 5, // user
			width: "10%",
		},
		{
			targets: 6, // user
			width: "10%",
		},
		
		
		],
		autoWidth: false,
		pageLength: 7,
	});

	 $("#addExam").click(function () {
		var examModal = new bootstrap.Modal(document.getElementById("examModal"), {
		backdrop: "static",
		keyboard: false,
		});
		examModal.show();
		$("#examForm")[0].reset();
		$("#examModal").on("shown.bs.modal", function () {
		$(".modal-title").html("Add Subject");
		$("#action").val("addExam");
		$("#save").val("Save");
		});
	});

	
	$("#add-row").on("click", ".update", function () {
		var id = $(this).attr("id");
		var action = "getExam";
		$.ajax({
		url: "ales_action.php",
		method: "POST",
		data: { id: id, action: action },
		dataType: "json",
		success: function (data) {
			$("#examModal").modal('show');
			$("#id").val(data.id);
			$("#subject").val(data.subject);
			$("#course").val(data.course_id);
			$(".modal-title").html("Edit Subject");
			$("#action").val("updateExam");
			$("#save").val("Save");
		},
		});
	});

	$("#examModal").on("submit", "#examForm", function (event) {
		event.preventDefault();
		$("#save").attr("disabled", "disabled");
		var formData = $(this).serialize();
		$.ajax({
		url: "ales_action.php",
		method: "POST",
		data: formData,
		success: function (data) {
			$("#examForm")[0].reset();
			$("#examModal").modal("hide");
			$("#save").attr("disabled", false);
			examRecords.ajax.reload();
		},
		});
	});

	
	$("#add-row").on('click', '.delete', function() {
		var id = $(this).attr("id");
		var action = "deleteExam";
	   
		swal({
		    title: "Are you sure?",
		    text: "Once deleted, you will not be able to recover this record!",
		    icon: "warning",
		    buttons: true,
		    dangerMode: true,
		})
		.then((willDelete) => {
		    if (willDelete) {
			 $.ajax({
			     url:"ales_action.php",
			     method: "POST",
			     data: { id: id, action: action },
			     success: function(data) {
				  var response = JSON.parse(data);
				  if (response.status === "success") {
				      examRecords.ajax.reload();
				      swal(response.message, { icon: "success" });
				  } else {
				      swal(response.message, { icon: "error" });
				  }
			     },
			     error: function() {
				  swal("Error deleting record!", { icon: "error" });
			     }
			 });
		    } else {
			 swal("Your record is safe!");
		    }
		});
	   });

});

</script>