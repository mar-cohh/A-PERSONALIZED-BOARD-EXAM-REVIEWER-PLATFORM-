$(document).ready(function () {
	var examRecords = $("#add-row").DataTable({
		"lengthChange": false,
		"processing": true,
		"serverSide": true,
		"bFilter": true,
		"serverMethod": "post",
		"order": [],
		"ajax": {
			"url": "blepts_action.php",
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
		url: "blepts_action.php",
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
		url: "blepts_action.php",
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


	$("#add-row").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deleteExam";
	   
		// Use SweetAlert for confirmation
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
			     url: "blepts_action.php",
			     method: "POST",
			     data: { id: id, action: action },
			     success: function(data) {
				  examRecords.ajax.reload();
				  swal("Record deleted successfully!", {
				      icon: "success",
				  });
			     },
			     error: function() {
				  swal("Error deleting record!", {
				      icon: "error",
				  });
			     }
			 });
		    } else {
			 swal("Your record is safe!");
		    }
		});
	   });
	   

});
