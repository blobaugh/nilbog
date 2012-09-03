$(document).ready(function(){

	/*$("#cms-navbar-back").css({  
  		"opacity": "0.7"
	});*/  
	$('#cms-navbar-back').fadeIn("slow");
	
	//$('img').tooltip();
/*	$("#cms-navbar").css({  
  		"opacity": "1"
	});*/

	$('#cms-navbar-selector').mouseenter(
		function() {
			// Mouse Over
			$('#cms-navbar').slideDown('fast');
		}
	);
	
	$('#cms-navbar').mouseleave(function(){
		$('#cms-navbar').slideUp('fast');
	});

}); // end $(document).ready