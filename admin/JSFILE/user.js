$(document).ready(function(){	
	var userRecords = $("#add-row").DataTable({
		lengthChange: false,
		processing:true,
		serverSide:true,	
		bFilter: false,	
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"user_action.php",
			type:"POST",
			data:{action:'listUsers'},
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
				targets: 1, // Exam Subject column
				width: "5%",
			},
			{
				targets: 2, // Total Questions column
				width: "5%",
			},
			{
				targets: 3, // Questions column
				width: "10%",
			},
			{
				targets: 4, // User column
				width: "10%",
			},
			{
				targets: 5, // Edit button column
				width: "15%",
			},
			{
				targets: 6, // Delete button column
				width: "15%",
			},
			{
				targets: 7, // Delete button column
				width: "15%",
			},
			],
			autoWidth: false,
			pageLength: 8,
	});	


	$(document).on('click', '.view', function() {
		var userId = $(this).attr('id');
		
		$.ajax({
			url: 'user_action.php', // Use your new endpoint
			type: 'POST',
			data: { id: userId, action: 'getUserDetails' }, // Specify the action
			dataType: 'json',
			success: function(data) {
				if (data.error) {
					alert(data.error);
					return;
				}
				$('#userList').html(`
					<tr>
						<td>${data.id}</td>
						<td>${data.first_name}</td>
						<td>${data.last_name}</td>
						<td>${data.email}</td>
						<td>${data.created || 'N/A'}</td>
					</tr>
				`);
				$('#userModal').modal('show'); // Show the modal
			},
			error: function(xhr, status, error) {
				console.error('Error fetching user details:', xhr.responseText);
				alert('Error fetching user details. Check console for details.');
			}
		});
	});

	$(document).on('click', '.approve', function(){
		var userId = $(this).attr("id");
		if(confirm("Are you sure you want to Approve this user?")) {
			$.ajax({
				url:"user_action.php",
				method:"POST",
				data:{id:userId, action: 'approve' },
				success:function(data){
					alert(data);
					location.reload(); // Reload the page to reflect changes
				}
			});
		}
	});

	$(document).on('click', '.delete', function(){
		var userId = $(this).attr("id");		
		var action = "deleteUser";
		if(confirm("Are you sure you want to Delete this User?")) {
			$.ajax({
				url:"user_action.php",
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