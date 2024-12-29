$(document).ready(function () {
	var questionsRecords = $('#add-row').DataTable({
		lengthChange: false,
		processing: true,
		serverSide: true,
		bFilter: false,
		serverMethod: "post",
		order: [],
		ajax: {
			url: "questionbanks_action.php",
			type: "POST",
			data: { action: 'listQuestions', 'subject_id': $('#add-row').attr('data-subject-id') },
			dataType: "json"
		},
		columnDefs: [
			{
				targets: [0, 3, 4],
				orderable: false,
				width: "5%",
			},
			{
				targets: 0,
				width: "5%",
			},
			{
				targets: 1,
				width: "25%",
			},
			{
				targets: 2,
				width: "25%",
			},
			{
				targets: 3,
				width: "20%",
			},
		],
		autoWidth: false,
		pageLength: 5,
	});

	$("#addQuestions").click(function () {
		var questionsModal = new bootstrap.Modal(document.getElementById("questionsModal"), {
			backdrop: "static",
			keyboard: false,
		});
		questionsModal.show();
		$("#questionsForm")[0].reset();
		$("#questionsModal").on("shown.bs.modal", function () {
			$(".modal-title").html("Add Questions");
			$('#subject_id').val($('#add-row').attr('data-subject-id'));
			$("#action").val("addQuestions");
			$("#save").val("Save");
		});
	});

	$("#add-row").on('click', '.update', function () {
		var id = $(this).attr("id");
		var action = 'getQuestion';
		$.ajax({
			url: 'questionbanks_action',
			method: "POST",
			data: { question_id: id, action: action },
			dataType: "json",
			success: function (respData) {
				// Set up the modal content
				$('#questionsForm')[0].reset();
				respData.data.forEach(function (item) {
					$('#id').val(item['question_id']);
					$('#subject_id').val($('#add-row').attr('data-subject-id'));
					$('#question_title').val(item['question']);
					$('#option_title_' + item['option']).val(item['title']);
					$('#answer_option').val(item['answer']);
				});
				$('.modal-title').html("Edit Questions");
				$('#action').val('updateQuestions');
				$('#save').val('Save');

				// Show the modal
				$("#questionsModal").modal({
					backdrop: 'static',
					keyboard: false
				}).modal('show');
				// Check the response status and display an alert
				if (response.status === "success") {
					alert(response.message); // Display success message
				} else {
					alert(response.message); // Display error message
				}
			}
		});
	});

	$("#questionsModal").on('submit', '#questionsForm', function (event) {
		event.preventDefault();
		$('#save').attr('disabled', 'disabled');
		var formData = $(this).serialize();
		
		$.ajax({
			url: "questionbanks_action.php",
			method: "POST",
			data: formData,
			dataType: "json", // Expecting a JSON response
			success: function (response) {
				// Check the response status and display an alert
				if (response.status === "success") {
					alert(response.message); // Display success message
				} else {
					alert(response.message); // Display error message
				}
				$('#questionsForm')[0].reset();
				$('#questionsModal').modal('hide');
				$('#save').attr('disabled', false);
				
				questionsRecords.ajax.reload(); // Reload the data table to reflect new questions
			},
			error: function () {
				alert('Failed to process the request.'); // Handle AJAX errors
				$('#save').attr('disabled', false); // Re-enable the save button
			}
		});
	});

	$("#csvImportForm").on('submit', function (e) {
		e.preventDefault();  // Prevent the default form submission
		var formData = new FormData(this);  // Get the form data, including the file
		formData.append("action", "importCSV");
		$.ajax({
			url: "questionbanks_action.php",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				alert(response);
				$('#csvImportForm')[0].reset();  // Reset the form after upload
				questionsRecords.ajax.reload();  // Reload the data table to reflect new questions
			},
			error: function () {
				alert('Failed to upload CSV.');
			}
		});
	});


	$("#add-row").on('click', '.delete', function () {
		var id = $(this).attr("id");
		var action = "deleteQuestions";
		if (confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url: "questionbanks_action.php",
				method: "POST",
				data: { id: id, action: action },
				success: function (data) {
					questionsRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});

	/* 
	$("#add-row").on('click', '.deletes', function(){
		var id = $(this).attr("id");		
		var action = "deleteQuestions";
	   
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
				url:"questionbanks_action.php",
				method:"POST",
				data:{id:id, action:action},
				success: function(data) {
					questionsRecords.ajax.reload();
					// Make sure you parse the data if it is JSON
					const response = JSON.parse(data);
					if (response.status === "success") {
						swal("Record deleted successfully!", {
						 icon: "success",
						});
					} else {
						swal("Error deleting record!", {
						 icon: "error",
						});
					}
				   },
				   
			 });
			} else {
			 swal("Your record is safe!");
			}
		});
	   }); */


});