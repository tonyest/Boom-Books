jQuery(document).ready( function($) {	
	$('.userselect').hide();
	$('button#issue_select').click(function (event) {
	$('.program').detach();
		event.preventDefault;
	$('.userselect').show();	
	});

	$('button#issue').click( function(event) {
	event.preventDefault();
	var json_str = 'action=boomb_ajax_content';
	json_str += '&'+$('form.issue :input').serialize();
			$.ajax({
			   type: "POST",
			dataType: "html",
			   url: MyAjax.ajaxurl,
			data: json_str,
			   success: function(html){
					$('#boomb_content').html(html);
			   }
			});	
	});
	
});