
$(document).ready(function(){
	
	var windowWidth = document.documentElement.clientWidth;  
	var windowHeight = document.documentElement.clientHeight;  
	var popupHeight = $("#cms-login").height();  
	var popupWidth = $("#cms-login").width();  
	//centering  
	$("#cms-login").css({  
	  "position": "absolute",  
	  "top": 5,  
	  "left": windowWidth/2-popupWidth/2
	});
	
	
	$("#cms-back-cover").css({  
	  "opacity": "0.7"
	});  
	$('#cms-back-cover').fadeIn("slow");
	$("#cms-login").fadeIn("slow");
	
	
	
	$("#cms-login-button").click(function(e){ 
		cms_validate_user();
	});

	
    $('#pass').keyup(function(e) {
		if(e.keyCode == 13) {
			cms_validate_user();
		}
	});
	
}); // end $(document).ready


function cms_validate_user() { 
	// Get and store user form values for future ease
	var user = $("#user").val();
	var pass = $("#pass").val();
	var password = $("#password").val();
	// Ensure user entered a user/pass. Attempt at bot control
	if(password == 'honeypower' && user != '' && user != '') {
		$.ajax({
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			url: HTTP_ROOT+"nilbog/controllers/login.php?q=login&user="+user+"&pass="+pass,
		    success: function(data) {
			  	if(data.success == 'true') {
						window.location.replace(location);
						
						return true;
			  	} else {
					// Stupid. why need twice?
					$("#cms-login-invalid").show('slow');
					return false;
			 	}
		    },
		  	error:function(XMLHttpRequest,textStatus, errorThrown) {
			   	  alert('There was some sort of error. Record the next 3 messages for your admin.');
			      alert(XMLHttpRequest.status);
			      alert(errorThrown);
			      alert(XMLHttpRequest.responseText);
		  	}
		
		   }); // end $.ajax
	} else { 
		// Stupid. why need twice?
		$("#cms-login-invalid").show('slow');
		return false;
	}
	
}