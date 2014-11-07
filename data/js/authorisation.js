FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
});


$(document).ready(function() {


	$('#authorisation_form').submit(function(event) {

		var formData = {
			'email' 			: $('input[name=email]').val(),
			'password' 			: $('input[name=password]').val()
		};
			
		$.ajax({
			type 		: 'POST',
			url 		: '/user/authorisation', 
			dataType 	: 'json', 
			encode      : true,
			data 		: formData
		}).done(function(data){
			if ((typeof data.error !== 'undefined')||( data.error !== '')){
			
			$('#error').html(data.error);	
			$('#error').show();
			}
			if (data.success){
					window.location = data.success_redirect;
			}
		});

		
		event.preventDefault();
	});

});