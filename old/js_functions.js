/*
my_dashboard : function () {
jQuery(document).ready( function($) {
	$(".update-nag").text('success biatch');
	/*
			var closed = $('.postbox').filter('.closed').map(function() { return this.id; }).get().join(','),
			//	hidden = $('.postbox').filter(':hidden').map(function() { return this.id; }).get().join(',');
				var hidden = 'dashboard_primary-hide';
			$.post(ajaxurl, {
				action: 'closed-postboxes',
				closed: closed,
				hidden: hidden,
				closedpostboxesnonce: jQuery('#closedpostboxesnonce').val(),
				page: 'dahsboard'
			});

			var postVars, page_columns = $('.columns-prefs input:checked').val() || 0;

			postVars = {
				action: 'meta-box-order',
				_ajax_nonce: $('#meta-box-order-nonce').val(),
				page_columns: page_columns,
				page: 'dashboard'
			}
			$('.meta-box-sortables').each( function() {
				postVars["order[" + this.id.split('-')[0] + "]"] = $(this).sortable( 'toArray' ).join(',');
			} );
			$.post( ajaxurl, postVars );
*/

		/* Callbacks */
//		pbshow : false,

//		pbhide : false
		
//old slider
function bb_slider_original(elms,min,max,step,init,width){
	$(elms).focus(function() {$(this).val('');}); //clear input on focus
	var $element = $(elms);
	var slider = $("<div id='slider'></div>").insertAfter( $element ).slider({
		animate: true,
		min: min,
		max: max,
		step: step,
		range: "min",
		value: init,
		slide: function( event, ui ) {
			$element.val(ui.value);
		}
	}).width(width);
	$element.change(function() {
		slider.slider( "value", this.value);
	});
}

/*
 *
 * clock functions
 *
 *
 */
function loadTime() {
		jQuery(document).ready(function($) {
		var today=new Date();
		var h=today.getHours();
		var m=today.getMinutes();
		var s=today.getSeconds();
		// add a zero in front of numbers<10
		m=checkTime(m);
		s=checkTime(s);
		$('#timenow').html(h+":"+m+":"+s);
		t=setTimeout('loadTime()',500);
	});	
};
/*
 *
 * highlight entire row on mouseover in table
 *
 *
 */
function checkTime(i) { if (i<10) {  i="0" + i; }; return i; }


function addLoadEvent(func){
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {window.onload = func;}
	else {window.onload = function(){oldonload();func();}}
}
//addLoadEvent(bb_menu_scripts);
//bb_dash_scripts();
//function bb_menu_scripts(){
	//}
	
	/*
	 *
	 *	SUBMIT SESSION
	 *			validate , ajax, callback, reset form.
	 *			submit changes to a session called from report or user_program
	 *
	 */
		$('button#submit_session').click( function(event) {
				event.preventDefault();
				//validate	
			if( $('ul.session li').size() > 1) {
				var json_str = 'action=boomb_submit&category=session&'+$('input.datepicker').serialize()+'&'+$('input.time').serialize()+'&parent='+setID;
				$.each( data , function ( index , value ) {
					$.each( value , function ( k , v ) {
						v['name'] = 'sets'+'['+index+']'+'['+v['name']+']';
					});
					json_str +=	'&'+$.param(value);			
				});

				$.ajax({
				   type: "POST",
				dataType: "html",
				   url: this.value,
				data: json_str,
				   success: function(msg){
						//success handler msg
						alert('Handler for .click() called.'+msg);
						//reset form
						reset_editor_fields();
						reset_session();
						data.length = 0;
						deleted_setIDs.length=0;
						setID = null;
				//					$('#boomb_program').html(html);
				   }
				});
			}		
		});