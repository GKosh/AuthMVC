
$(document).ready(function() {
	
	$('#registration_form').submit(function(event) {
		var formData = {
			'email' 			: $('input[name=email]').val(),
			'password' 			: $('input[name=password]').val()
		};
		

	var passRegexp = /^[A-Za-z]\w{6,14}$/;  
		
	var emailRegexp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

	if (!emailRegexp.test(formData.email)){
		$('#required1').html('Please provide a valid email address');	
		$('#required1').show();
		return false} else{$('#required1').hide()};
	if (!passRegexp.test(formData.password)){
		$('#required2').html('Please provide password 6 to 15 characters with numbers, letters and/or underscore, upper and lower case');	
		$('#required2').show();
		return false} else{$('#required2').hide()};
	if ($('#pass_confirm').val() !== formData.password) {
		$('#required3').html('Passwords don\'t match');	
		$('#required3').show();
		return false} else{$('#required3').hide()};

		$.ajax({
			type 		: 'POST',
			url 		: '/user/registration', 
			dataType 	: 'json', 
			encode      : true,
			data 		: formData
		}).done(function(data){
			if (data.success){
				window.location = data.success_redirect;
			} else {
				$('#error').html(data.error);	
				$('#error').show();
			}
		});

		
		event.preventDefault();
	});

});