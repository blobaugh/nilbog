$(document).ready(function(){
//	$("a[rel='example1']").colorbox();

	// Find all elements with class="cms-editable"
	// Add the edit icon to each element found
	// Create the click event that allows the element to be edited
	$(document).find('.cms-editable').each(function(ind) {

		$('body').append('<img src="'+HTTP_ROOT+'nilbog/images/edit.png" class="cms-edit cms-edit' + ind + '" edit="'+$(this).attr('id')+'"/>');
		$(this).addClass('cms-editable-' + ind)
		$(".cms-edit"+ind).position({
			  my: "right center",
			  at: "left top",
			  of: ".cms-editable-"+ind,
			  collision: "fit",
			  offset: "10 10"
		});
		
		$('.cms-edit'+ind).click(function(ind) {
		//	$(this).text($(this).val()); 
		
			edit_content($(this).attr('edit'));
		});
	});
});

/*
 * Sets up everything needed to edit the current page's content
 *
 * @param String id
 */
function edit_content(id) {
	$.fancybox({
		'type': 'iframe',
		'href': HTTP_ROOT+"nilbog/controllers/edit.php?id="+$('body').attr('id')+"&element="+id,
		'width': 960,
		'height': 615,
		'padding':0
	});
	
}