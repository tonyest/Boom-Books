jQuery(document).ready( function($) {
	var data = new Array();		//data container for final ajax submit
	var deleted_setIDs = new Array();
	var sessionID = null;
	var parent = null;		//to die in the cleanup
	var category = null;
/*
 *
 *	WIDGET ENVIRONMNENT INIT
 *			instantiate datepickers, hide edit form.
 *
 */
	$('.bb-datepicker').datepicker({dateFormat: 'D dd-M-yy'});
	$('.datepicker').datepicker({dateFormat: 'D dd-M-yy'});		//initialise datepickers
	$('form.editor').hide();		//initialise editor as hidden
	$('button.session_submit').hide();		//hide submit button
	$(".editor-header a.delete-session").hide(); //hide session delete	
	$(':input').focus(function() {		//clear input on focus
		if ($(this).hasClass('default')){
			$(this).val('');
			$(this).removeClass('default');
		}
	});
/*
 *
 *	SAVE BUTTON
 *			save input data to container[data] , reset form and generate <li> set row
 *
 */
	$('button#save_set').click( function () {
		var row_index = $('button#save_set').val();
		if( row_index != $('ul.session li:last').index() ) {
			$('ul.session li:eq('+row_index+')').detach();
			data.splice( row_index , 1 , $('form.editor :input').serializeArray() );	//	insert updated dataset at index			
		} else if ( row_index == $('ul.session li:last').index() ) {
			data.push($('form.editor :input').serializeArray());		//insert dataset at end of array				
		}
		var setrow = '<li class="set"><a href="edit">';
		$.each( data[row_index] , function ( index , key ) {	//iterate through data object and output DOM set
			var name = key['name'];
			var value = key['value'];
			switch ( name ) {
				case 'sport' : setrow += value + '-';
					break;
				case 'setting' : setrow += value + ' ';
					break;
				case 'distance' : setrow += value + 'km ';
					break;
				case 'duration' : setrow += value + ' ';
					break;
				case 'difficulty' : setrow += '@' + value;
					break;
			}
		});
		$('form.editor').hide();
		$('ul.session li:eq('+row_index+')').before(setrow+'</a> <a href="delete" class="delete">[x]</a></li>');
		$('button.session_submit').show();		
		if ( parent > 0 ) {
			$('ul.session li a.delete').hide();	//editing of session format not allowed with programs
		}
	});
/*
 *
 *	SET LINKS
 *			custom link actions for sets {add , delete , edit}
 *
 */
	$("ul.session li a").live( 'click' , function(event) {
		event.preventDefault();
		switch ( $(this).attr('href') ) {
			case 'delete':
				$('button.session_submit').show();	
				if ( $('ul.session li.set').size() <= 1 ) {
					$('button.session_submit').hide();
				}
				var filter = $.grep( data[$(this).parent().index()] , function( n , i) {
					if ( n.name == 'setID' ) {
						return n;
					}
				})
				deleted_setIDs.push(filter);
				data.splice( $(this).parent().index() , 1 );		//remove indexed values from data array
				$(this).parent().detach();		//remove inexed DOM elm row
				break;
			case 'edit':
				$('button.session_submit').hide();
				var row_index = $(this).parent().index();
				$('form.editor').show();
				$.each( data[row_index] , function ( index , key ) {
					var name = key['name'];
					var value = key['value'];
					$('.editor [name="'+name+'"]').val(value);
				});					
				$('button#save_set').val(row_index);
				break;
			case 'add':
				$('button.session_submit').hide();
				$('form.editor').show();
				$('button#save_set').val($('ul.session li:last').index());
				//		re-init input box values and default class trigger
				reset_editor_fields();
				break;
			case 'reset':
				data.length = 0;		//remove indexed values from data array
				parent = null;			//clear setID
				category = null;
				sessionID = null;
				deleted_setIDs.length = 0;
				reset_editor_fields();	//reset editor form to defaults
				reset_session();
				$('input[name="day"]').val('Day no.');
				$('input[name="day"]').addClass('default');						
				break;				
		}
	});
	
/*
 *
 *	DELETE SESSION LINK
 *	
 *
 */
	$(".editor-header a.delete-session").live( 'click' , function(event) {
		event.preventDefault();
		var answer = confirm('Delete entire Session?');
		if ( answer && sessionID != null ) {
			var json_str = 'action=boomb_delete_session&sessionID='+sessionID+'&'+$('input#programID').serialize();
			$.ajax({
			   	type: "POST",
				dataType: "html",
			   	url: $(this).attr('href'),
				data: json_str,
			   success: function(html){
					//reset form
					reset_editor_fields();
					reset_session();
					data.length = 0;
					deleted_setIDs.length=0;
					sessionID = null;
					$('#boomb_program').html(html);
					$('form.editor').hide();
					$('button.session_submit').hide();
					$('ul.session li.set').detach();	//remove set rows
					$('ul.session li a.add').show(); //re-enable add link if was disabled
					$(".editor-header a.delete-session").hide();
			   }
			});
		}
	});		
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
			var json_str = 'action=boomb_user_submit&category='+category+'&parent='+parent+'&sessionID='+sessionID+'&'+$('input.datepicker').serialize()+'&'+$('input.time').serialize();
			$.each( data , function ( index , value ) {
				$.each( value , function ( k , v ) {
					v['name'] = 'sets'+'['+index+']'+'['+v['name']+']';
				});
				json_str +=	'&'+$.param(value);
			});
			//deleted sets
			$.each( deleted_setIDs , function ( index , value ) {
				$.each( value , function ( k , v ) {
					v['name'] = 'deleted_setIDs'+'['+index+']'+'['+v['name']+']';
				});
				json_str +=	'&'+$.param(value);			
			});
			$.ajax({
			   type: "POST",
			dataType: "html",
			   url: this.value,
			data: json_str,
			   success: function(html){
					//success handler msg
					//reset form
					reset_editor_fields();
					reset_session();
					data.length = 0;
					deleted_setIDs.length=0;
					parent = null;
					category = null;
					$('#boomb_content').html(html);
			   }
			});
		}		
	});			
/*
 *
 *	SUBMIT PROGRAM
 *			validate , ajax, callback, reset form.
 *			submit changes to a session called from program author
 *
 */
	$('button#submit_program').click( function(event) {
			event.preventDefault();
			//validate	
		if( $('ul.session li').size() > 1) {
			var json_str = 'action=boomb_program_submit&'+$('input.day').serialize()+'&'+$('input.time').serialize()+'&'+$('input#programID').serialize()+'&sessionID='+sessionID;
			$.each( data , function ( index , value ) {
				$.each( value , function ( k , v ) {
					v['name'] = 'sets'+'['+index+']'+'['+v['name']+']';
				});
				json_str +=	'&'+$.param(value);			
			});
			//deleted sets
			$.each( deleted_setIDs , function ( index , value ) {
				$.each( value , function ( k , v ) {
					v['name'] = 'deleted_setIDs'+'['+index+']'+'['+v['name']+']';
				});
				json_str +=	'&'+$.param(value);			
			});

			$.ajax({
			   type: "POST",
			dataType: "html",
			   url: this.value,
			data: json_str,
			   success: function(html) {
					//reset form
					reset_editor_fields();
					reset_session();
					data.length = 0;
					deleted_setIDs.length=0;
					sessionID = null;
					category = null;
					parent = null;
					$('#boomb_content').html(html);
					$('input[name="day"]').val('Day no.');
					$('input[name="day"]').addClass('default');
			   }
			});
		}		
	});
/*
 *
 *	NAVIGATION LINKS				BOOMB_DEPRECATED
 *			set custom actions for navigation links
 *
 */
	$('ul.navigation li a').live( 'click' , function () {
	//	$(this).attr('href')
	//	event.preventDefault;
		
	});
/*
 *
 *	EDIT USER PROGRAM
 *			open selected program set in editor and set environment for this scenario
 *
 */
	$('tr.set-row-incomplete').live( 'click' , function (event) {
		event.preventDefault;
		$('form.editor').hide();				
		$('ul.session li a.add').hide();	//editing of session format not allowed with programs		
		$('button.session_submit').hide();
		data.length = 0;		//remove indexed values from data array
		parent = null;
		$('ul.session li.set').detach();	//remove set rows
		reset_editor_fields();	//reset editor form to defaults
		//ajax request for set data
		$.ajax({
		   type: "POST",
		dataType: 'json',
		   url: $(this).attr('href'),
		data: 'action=get_session&'+$(this).find('input').serialize(),
		   success: function(sets) {
				//success handler get data and assign to relevant fields
				data = sets;
				$.each( data , function ( index , element ) {
					var setrow = '<li class="set"><a href="edit">';
					var sport, setting, distance, duration, difficulty, start_date, start_time;
						$.each( element , function ( index , key ) {	//iterate through data object and output DOM set
						var name = key['name'];
						var value = key['value'];
						switch ( name ) {
							case 'sport' : sport = value + '-';
								break;
							case 'setting' : setting = value + ' ';
								break;
							case 'distance' : distance = value + 'km ';
								break;
							case 'duration' : duration = value + ' ';
								break;
							case 'difficulty' : difficulty = '@' + value+'%';
								break;
							case 'start_date' : start_date = value;
								break;
							case 'start_time' : start_time = value;
								break;
							case 'sessionID' : parent = value;
								break;
							case 'category' : category = value;
								break;
						}
					});
					setrow += sport+setting+distance+duration+difficulty;
					$('ul.session li:last').before(setrow+'</a> <a href="delete" class="delete">[x]</a></li>');
					$('input.datepicker').val(start_date);
					$('input.time').val(start_time);
					$('input.default').removeClass('default');
					$('ul.session li a.delete').hide();	//editing of session format not allowed with programs
						
				});
			}
		});

	});
/*
*
*	EDIT SESSSION   -  Combine this with edit program
*			open selected program set in editor and set environment for this scenario
*
*/
	$('table.boomb-session tr.session').live( 'click' , function (event) {
		event.preventDefault;
		$('form.editor').hide();				
		$('button.session_submit').hide();
		data.length = 0;		//remove indexed values from data array
		parent = null;
		$('ul.session li.set').detach();	//remove set rows
		reset_editor_fields();	//reset editor form to defaults
		//ajax request for set data
		$.ajax({
		   type: "POST",
		dataType: 'json',
		   url: $(this).attr('href'),
		data: 'action=get_session&'+$(this).find('input').serialize(),
		   success: function(sets) {
				//success handler get data and assign to relevant fields
				data = sets;
				$.each( data , function ( index , element ) {
					var setrow = '<li class="set"><a href="edit">';
					var sport, setting, distance, duration, difficulty, start_date, start_time;
						$.each( element , function ( index , key ) {	//iterate through data object and output DOM set
						var name = key['name'];
						var value = key['value'];
						switch ( name ) {
							case 'sport' : sport = value + '-';
								break;
							case 'setting' : setting = value + ' ';
								break;
							case 'distance' : distance = value + 'km ';
								break;
							case 'duration' : duration = value + ' ';
								break;
							case 'difficulty' : difficulty = '@' + value+'%';
								break;
							case 'start_date' : start_date = value;
								break;
							case 'start_time' : start_time = value;
								break;
							case 'sessionID' : sessionID = value;
								break;
							case 'category' : category = value;
								break;
						}
					});
					setrow += sport+setting+distance+duration+difficulty;
					$('ul.session li:last').before(setrow+'</a> <a href="delete" class="delete">[x]</a></li>');
					$('input.datepicker').val(start_date);
					$('input.time').val(start_time);
					$('input.default').removeClass('default');
				});
			}
		});

	});

/*
 *
 *	EDIT PROGRAM IN AUTHOR
 *			open selected program set in editor and set environment for this scenario
 *
 */
	$('tr.program-session').live( 'click' , function (event) {
		event.preventDefault;
		$('form.editor').hide();						
		$('button.session_submit').hide();
		data.length = 0;		//remove indexed values from data array
		sessionID = null;
		$('ul.session li.set').detach();	//remove set rows
		reset_editor_fields();	//reset editor form to defaults
		//ajax request for set data
		$.ajax({
		   type: "POST",
		dataType: 'json',
		   url: $(this).attr('href'),
		data: 'action=get_program_sessions&'+$(this).find('input').serialize(),
		   success: function( sets ) {
				//success handler get data and assign to relevant fields
				data = sets;
				$.each( data , function ( index , element ) {
					var setrow = '<li class="set"><a href="edit">';
					var sport, setting, distance, duration, difficulty, day, time;
						$.each( element , function ( index , key ) {	//iterate through data object and output DOM set
						var name = key['name'];
						var value = key['value'];
						switch ( name ) {
							case 'sport' : sport = value + '-';
								break;
							case 'setting' : setting = value + ' ';
								break;
							case 'distance' : distance = value + 'km ';
								break;
							case 'duration' : duration = value + ' ';
								break;
							case 'difficulty' : difficulty = '@' + value+'%';
								break;
							case 'day' : day = value;
								break;
							case 'time' : time = value;
								break;
							case 'sessionID' : sessionID = value;
								break;
						}
					});
					setrow += sport+setting+distance+duration+difficulty;
					$('ul.session li:last').before(setrow+'</a> <a href="delete" class="delete">[x]</a></li>');
					$('input.day').val(day);
					$('input.time').val(time);
					$('input.default').removeClass('default');
					$(".editor-header a.delete-session").show();
					$(".editor-header h3").html('Edit Session');					
				});
			}
		});

	});



});


// - - - - - Functions - - - - - -//

/*
 *
 *		RESET EDITOR FIELDS
 *			reset editor fields to default
 *
 */
function reset_editor_fields () {
	jQuery(document).ready( function($) {
		
		$('.editor input[name="setID"]').val(null);	
		$('input[name="distance"]').val('km');
		$('input[name="distance"]').addClass('default');
		$('input[name="duration"]').val('hh:mm:ss');
		$('input[name="duration"]').addClass('default');
		$('input[name="difficulty"]').val('RPE%');
		$('input[name="difficulty"]').addClass('default');
		$('input[name="water"]').val('Litres');
		$('input[name="water"]').addClass('default');
		$('input[name="foods"]').val('comma separated values');
		$('input[name="foods"]').addClass('default');
		$('textarea[name="details"]').val('');
		$('textarea[name="details"]').addClass('default');
		$('input.time').val('hh:mm:ss');
		$('input.time').addClass('default');	
	});
}
/*
 *
 *		RESET SESSION
 *			format session data container
 *
 */
function reset_session () {
	jQuery(document).ready( function($) {
		$(".editor-header h3").html('Add New Session');		
		$('ul.session li a.add').show();
		$("ul.session li.set").detach();
		$('form.editor').hide();	
		$('button.session_submit').hide();
		$('button#submit_program').hide();
		$(".editor-header a.delete-session").hide();
	});
}




