
$('document').ready(function(){

	$('#users_form').submit(function(event) {
		event.preventDefault();
	});
	
	$('#logout').click(function(){
	
		$.ajax({
			type 		: 'POST',
			dataType 	: 'json', 
			encode      : true,
			url 		: '/user/users/logout'
		}).done(function(data){
			window.location = data.success_redirect;
		});

	});
	  
	$('#addNew').click(function(){
			var formData = {
			'email' 			: $('input[name=email]').val(),
			'password' 			: $('input[name=password]').val()
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/user/users/add', 
			data 		: formData
		}).done(function(result){
			$('#tableBody').html(result);
		});

	});
	
	$('#update').click(function(){
	var selected_id = parseInt($('#radio:checked').val());
	if (typeof selected_id ==="undefined"){alert('Plese select user in order to update!');}
	else{		
		var formData = {
			'id'				:selected_id ,
			'email' 			: $('input[name=email]').val(),
			'password' 			: $('input[name=password]').val()
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/user/users/update', 
			data 		: formData
		}).done(function(result){
			$('#tableBody').html(result);
		});
		}
	});
	

	$('body').on('click','.delete',function(){	
		$(this).parent().find( ".radio").prop( "checked", true );
			var selected_id = parseInt($('#radio:checked').val());
			if (typeof selected_id ==="undefined"){alert('Plese select client in order to delete!');}
			else{
			if(window.confirm("Are you sure you want to delete this client?")){
				$.ajax({
				type	: 'POST',
				url 	: '/user/users/delete', 
				data	: {
				'id'	:selected_id
				}
				}).done(function(result){
					$('#tableBody').html(result);
				});
				}
			}
	});
});