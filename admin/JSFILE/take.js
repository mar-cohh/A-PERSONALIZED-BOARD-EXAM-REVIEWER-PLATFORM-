$(document).ready(function(){
	var userRecords = $('#add-row').DataTable({
		"lengthChange": false,
		"processing": true,
		"serverSide": true,        
		"bFilter": false,
		'serverMethod': 'post',		
		"order":[],
		"ajax": {
		    url: "take_action.php",
		    type: "POST",
		    data:{action:'getExamEnroll', 'subject_id':$('#add-row').attr('data-subject-id')},
		    dataType:"json"
		},
		columnDefs: [
			{
				targets: [0, 4, 5, 6, 7], // Targeting ID, and action buttons columns
				orderable: false, // Disable sorting for these columns
				width: "5%", // Set a default width for these columns
			},
			{
				targets: 0, // ID column
				width: "5%",
			},
			{
				targets: 1, // NAME
				width: "5%",
			},
			{
				targets: 2, // SUBJECT
				width: "5%",
			},
			{
				targets: 3, // COURSE
				width: "5%",
			},
			{
				targets: 4, // SCORE
				width: "10%",
			},
			{
				targets: 5, // TOTAL QUESTION 
				width: "15%",
			},
			{
				targets: 6, // TAKE DATE
				width: "15%",
			},
			{
				targets: 7, // TAKE DATE
				width: "15%",
			},
			],
			autoWidth: false,
			pageLength: 8,
	});  

	$(document).on('click', '.delete', function(){
		var userId = $(this).attr("id");		
		var action = "deleteUser";
		if(confirm("Are you sure you want to Delete this User?")) {
			$.ajax({
				url:"take_action.php",
				method:"POST",
				data:{userId:userId, action:action},
				success:function(data) {					
					userRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	

});