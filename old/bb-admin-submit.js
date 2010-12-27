/*
jQuery(document).ready(function($){	
	//GENERAL MENU FUNCTIONS
	bb_datepicker('.bb-datepicker');

	
	//SUBMIT FUNCTIONS
	tab_efforts('#tabs');
	set_submit();
//		time_inputs();

	//	bb_slider('.duration',1,4*60,1,0,250);//#[elms],min,max,step,init,width
	bb_slider('.distance',1,50,1,0,250,true);
	bb_slider('.difficulty',1,10,1,0,250,true);
	bb_slider('.water',1,6,.1,0,250,true);
	set_totals();
	$( "#tabs").tabs('select', '#tabs-1');//select new tab
});

*/
function bb_datepicker(elms){
	$(elms).datepicker({dateFormat: 'D dd-M-yy'});
}

/*
 *
 * insert sliders
 *
 *
 */
function bb_slider(elms,min,max,step,init,width,init){
	$('td.new-effort ' + elms).focus(function() {$(this).val('');}); //clear input on focus
	var $element = $('td.new-effort ' + elms);
	var $insert_element = (!init || false == init)? $($element).last() : $element ;
	var slider = $("<div id='slider'></div>").insertAfter( $insert_element ).slider({
		animate: true,
		min: min,
		max: max,
		step: step,
		range: "min",
		value: init,
		slide: function( event, ui ) {
			var $tabs = $('#tabs').tabs();
			var index = $tabs.tabs('option', 'selected');			
			$( $element.get(index-1) ).val(ui.value);
			//also change values of set-row results
			//format for meteres - kilometers
			if( 'swim' ==  $( $('table#bb_submit_table tr.set-row td.sport').get(index-1) ).text() && '.distance' == elms ){
				jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find(elms).html(ui.value/1000);
			} else {
				jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find(elms).html(ui.value);
			}
			set_totals();//update totals in set-row
		}
	}).width(width);
	$element.change(function() {
		//validate sliders on text input
		if(isDecimal( this.value )){
			if( ('.difficulty' == elms || '.water' == elms) && this.value >10 ){
				$(this).val('0');
				slider.slider( "value", 0 );
			} else {
				slider.slider( "value", this.value );
			}
		} else {
			$(this).val('0');	
			slider.slider( "value", 0 );
		}	
		//also change values of row results
		var $tabs = $('#tabs').tabs();
		var index = $tabs.tabs('option', 'selected');
		//format for meteres - kilometers
		if( 'swim' == $($('table#bb_submit_table tr.set-row td.sport').get(index-1)).text() && '.distance' == elms ){
			jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find(elms).html(this.value/1000);
		} else {
			jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find(elms).html(this.value);						
		}
		set_totals();//update totals in set-row
	});
}
/*
 *
 * stylize the difficulty
 *
 *
 */
function stylize_difficulty(elm){
	$(elm).each(function(index) {
		switch ( $(this).text() ) {
			case '1':	$(this).css({'color': 'hsla( 240, 100%, 30% , 1 )'});
			break;
			case '2': 	$(this).css({'color': 'hsla( 240, 100%, 30% , 1 )'});
			break;
			case '3': 	$(this).css({'color': 'hsla( 150, 100%, 30% , 1 )'});
			break;
			case '4': 	$(this).css({'color': 'hsla( 150, 100%, 30% , 1 )'});
			break;
			case '5': 	$(this).css({'color': 'hsla( 30, 100%, 45% , 1 )'});
			break;
			case '6': 	$(this).css({'color': 'hsla( 30, 100%, 45% , 1 )'});
			break;
			case '7': 	$(this).css({'color': 'hsla( 60, 70%, 50% , 1 )'});
			break;
			case '8': 	$(this).css({'color': 'hsla( 60, 100%, 63% , 1)'});
			break;
			case '9': 	$(this).css({'color': 'hsla( 0, 100%, 50% , 1 )'});
			break;
			case '10': 	$(this).css({'color': 'hsla( 0, 100%, 50% , 1 )'});
			break;
			default: $(this).css({'color': 'hsla( 150, 100%, 30% , 1 )'});
			break;
		}
	});
}

/*
 *EFFORTS - ALLOWS USER TO ADD NEW EFFORTS INTO THE JQUERY TAB STRUCTURE AND 
 * FILL THEM WITH THEIR EXERCISE DATA.  DATA IS THEN SUBMITTED TO BOOMBOOKS DATABASE
 *
 *CAN ACCEPT A REFERRED SET FROM PROGRAM WHICH HAS LESS FREEDOM
 */
function tab_efforts(elm){
	var $sport = $( ".new-effort#sport");
	var tab_counter = $('div[id^="tabs-"]').size()+1;

	// tabs init with a custom tab template and an "add" callback filling in the content
	var $tabs = $( "#tabs").tabs({
		tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>",
		add: function( event, ui ) {
			var table = add_effort_editor(tab_counter-1,$sport.val());
			$( ui.panel ).append( "<p>" + table + "</p>" );
				switch($sport.val()){
				case 'cycle' :
					bb_slider('.distance',1,300,1,0,250);
				break;
				case 'swim' :
					bb_slider('.distance',25,100*100,50,0,250);
				break;
				case 'run' :
					bb_slider('.distance',1,50,1,0,250);
				break;
			}
			bb_slider('.difficulty',1,10,1,0,250);
			bb_slider('.water',1,6,.1,0,250);
			time_inputs();
			add_set_row($sport.val());//add row to set totals
			update_location('.setting');//update location/setting on change
			$tabs.tabs('select', '#' + ui.panel.id);//select new tab
		}
	});
	// actual addTab function: adds new tab using the title input from the sport select element
	function addTab() {
		if( '' != $sport.val()){
			$tabs.tabs( "add", "#tabs-" + tab_counter, $sport.val() );
			tab_counter++;
			$('.update').slideUp('slow','swing');
		}else{
			bb_ajax_i18n_update('Select a sport before adding a new effort.');
		}
	}

	// addTab button: adds a new effort tab with sport title and table input contents
	$( "#add_effort" )
		.button()
		.click(function() {
			addTab();
		});

	// close icon: removing the tab on click
	// remove corresponding total row
	// note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
	$( "#tabs span.ui-icon-close" ).live( "click", function() {
		var index = $( "li", $tabs ).index( $( this ).parent() );
		jQuery($('table#bb_submit_table tr.set-row').get(index)).remove();
		set_totals();
		$tabs.tabs( "remove", index );
	});
}
/*
 *
 * Adds new row to the set totals in first tab
 *
 *
 */
function add_set_row(sport) {
$lastrow = $('table#bb_submit_table tr.set-row').last();
	$('<tr class="bb-submit set-row">'+
		'<td class="bb-submit sport">	'+sport+'	</td>'+
		'<td class="bb-submit setting">		-		</td>'+
		'<td class="bb-submit distance">	-		</td>'+
		'<td class="bb-submit duration"><span id="h">-</span>:<span id="m">-</span>:<span id="s">-</span></td>'+
		'<td class="bb-submit difficulty">	-		</td>'+
		'<td class="bb-submit water">	-		</td>'+
	'</tr>').insertAfter($lastrow);
	return ;	
}
/*
 *
 * Template for effort editor
 *
 *
 */
function add_effort_editor(tab_counter,sport) {
	var dist_units = ( 'swim' == sport)? 'meters' : 'kilometers';
	var table = '<input type="hidden" name="effort['+tab_counter+'][sport]" value="'+sport+'" class="bb-submit new-effort sport" id="sport" /><table class="bb-submit new-effort" id="submit_form_table"><tr><td class="bb-submit new-effort col-1" >'+
		'<select name="effort['+tab_counter+'][setting]" value="$effort=>setting" class="bb-submit new-effort setting" id="setting" ><option value="">Select location...</option><option value="road">road</option><option value="gym">gym</option><option value="pool">pool</option><option value="open_water">open water</option><option value="wind_trainer">wind trainer</option></select>'+
		'<p><strong>Foods consumed</strong><br /><input type="text" name="effort['+tab_counter+'][foods]" value="" autocomplete="on" class="bb-submit new-effort food" id="food" /></p>'+
		'</td><td class="bb-submit new-effort col-2" ><strong class="new-effort duration">Time<input type="text" value = "hh" name ="effort['+tab_counter+'][h]" class="bb-submit new-effort duration" id="h" />:<input type="text" value = "mm" name="effort['+tab_counter+'][m]" class="bb-submit new-effort duration" id="m" />:<input type="text" value = "ss" name="effort['+tab_counter+'][s]" class="bb-submit new-effort duration" id="s" /></strong></td></tr>'+
//		'</td><td class="bb-submit new-effort col-2" ><strong>Time</strong><input type="text" name="effort['+tab_counter+'][duration]" autocomplete="on" value="minutes" class="bb-submit new-effort duration" id="duration" /></td></tr>'+
		'<tr><td rowspan="3" class="bb-submit new-effort col-1" ><strong>Describe the session</strong><br /><textarea name="effort['+tab_counter+'][details]" class="bb-submit new-effort details" id="details" ></textarea>'+
		'</td><td class="bb-submit new-effort col-2"><strong>Distance</strong>'+
		'<input type="text" name="effort['+tab_counter+'][distance]" autocomplete="on" value="'+dist_units+'" class="bb-submit new-effort distance" id="distance" />'+
		'</td></tr><tr><td class="bb-submit new-effort col-2"><strong>Difficulty</strong><input type="text" name="effort['+tab_counter+'][difficulty]" autocomplete="on" value="out of 10" class="bb-submit new-effort difficulty" id="difficulty" />'+
		'</td></tr><tr><td class="bb-submit new-effort col-2"><strong>Water</strong><input type="text" name="effort['+tab_counter+'][water]" autocomplete="on" value="litres" class="bb-submit new-effort water" id="water" /></td></tr></table>';
	return table;
}
/*
 *
 * live update for set totals in first tab
 *
 *
 */
function set_totals(){
	//init variables
	var distance = 0 , h = 0 , m = 0 , s = 0 , difficulty = 0, avg_index = 0 , water = 0;
	
	//total distance if returns int
	$('table#bb_submit_table tr.set-row td.distance').each(function(index,elm) {
		if( !isNaN( parseInt( $(elm).html() ) ) ){
	    	distance += parseFloat( $(elm).html() );
		}

	});
	$('table#bb_submit_table tr.total-row td.distance').html(distance + ' kms');
	
	//total durations if returns int
	$('table#bb_submit_table tr.set-row td.duration span#h').each( function( index,elm ) {
		if( isUnsignedInteger( $(elm).text() ) ) {
			h += parseInt( $(elm).text() );
		}
	});
	$('table#bb_submit_table tr.set-row span#m').each( function( index,elm ) {
		if( isUnsignedInteger( $(elm).text() ) ) {
		m += parseInt( $(elm).text() );
		}
		});
	$('table#bb_submit_table tr.set-row span#s').each( function( index,elm ) {
		if( isUnsignedInteger( $(elm).text() ) ) {
			s += parseInt( $(elm).text() );
		}
	});
		h += Math.floor( ( m + Math.floor(s/60) )/60 );
		m = (m + Math.floor(s/60))%60;
		s = s%60;
	$('table#bb_submit_table tr.total-row td.duration').html( zero_pad(h) + ":" + zero_pad(m) + ":" + zero_pad(s) );
	
	//average difficulty if returns int
	$('table#bb_submit_table tr.set-row td.difficulty').each(function(index,elm) {
		if( !isNaN( parseInt( $(elm).html() ) ) ){
	    	difficulty += parseInt( $(elm).html() );
			avg_index++;
		}
	});
	if(0 != avg_index){
		difficulty = difficulty/avg_index;
	}
	$('table#bb_submit_table tr.total-row td.difficulty').html( 'avg ' + difficulty.toFixed(1));
	
	//total water if returns int
	$('table#bb_submit_table tr.set-row td.water').each(function(index,elm) {
		if( !isNaN( parseFloat( $(elm).html() ) ) ){
	    	water += parseFloat( $(elm).html() );
		}
	});
	$('table#bb_submit_table tr.total-row td.water').html(water.toFixed(1) + ' L');
	stylize_difficulty('.difficulty');
}
/*
 *
 * live update for location in first tab
 *
 *
 */
function update_location (elm) {
	$("select"+elm).change(function () {
		var $tabs = $('#tabs').tabs();
		var index = $tabs.tabs('option', 'selected');
		var str = $($("select"+elm+" option:selected").get(index-1)).val();
		if("" != str ){
			jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find(elm).html(str);
		}
	}).change();
}
/*
 *
 * 
 *wrap a new set.....                    Non fUNCTIONAL
 *
 */
function bb_newset(elms){
	$(elms).button();
	var wrap = elms+"-wrap";
	$(elms).click(function() {
		var i = $('.new-effort').size()+1;//set no.
		$('.new-effort').unwrap();
		var element = '#new-effort'+i;
		var set = $("<input type='radio' class='bb-effort new-effort' id='effort"+i+"' name='effort'><label for='effort"+i+"'>effort-"+i+"</label>").insertBefore(elms);
		$('.new-effort').wrapAll('<div class="bb-effort effort" id="effort"/>');
		$('#effort').buttonset();
//		$("#effort+label").focus();
	});
}

/*
 *
 * selects all input items from page and uses AJAX to submit to wordpress
 *
 *
 */
function set_submit(){
	$('#submit_set').click(function(){
		
		var wp_ajax_url = $('a#wpurl_ajax').attr('href');
		var input_data =$(':input').serialize();
		if(submit_set_validate()){
			$.ajax({
			   type: "POST",
			dataType: "html",
			   url: wp_ajax_url,
			data:'action=bb_submit&' + input_data,
			   success: function(msg){
					$('div.bb-submit#bb_update').html('<div class="updated" align="center"><p><strong>'+msg+'</strong></p></div>');
					$('.updated').hide();
					$('.updated').fadeIn('slow');
			   }
			});
		}
	});
}
/*
 *
 * internationalise a string through an AJAX to wordpress
 *
 *
 */
function bb_ajax_i18n_update(string){
	
	var wp_ajax_url = $('a#wpurl_ajax').attr('href');
	var return_string = $.ajax({
	   	type: "POST",
		 url: wp_ajax_url,
		data: 'action=bb_ajax_i18n_update&string=' + string,
		success: function(i18n_string){
			$('div.bb-submit#bb_update').html('<div class="updated" align="center"><p><strong>'+i18n_string+'</strong></p></div>');
			$('.updated').hide();
			$('.updated').fadeIn('slow');
		}
	});
}

/*
 *
 * validate time inputs
 *
 *
 */
function time_inputs() {
	$('.duration').click( function() {
		if( $(this).val() == '00' || !isUnsignedInteger( $(this).val() ) ){
			$(this).val('');
		}
	});
	
	$('.duration').change( function() {
 		if ( !isUnsignedInteger( this.value ) && this.value != 0 ){
			$(this).val(0);
		}
		if ( $(this).attr('id') == 'h' && this.value > 24 ) {
			$(this).val(0);
		}
		else if ( this.value > 60 ){
			$(this).val(0);
		}	
		if(this.value != '00' )
		$(this).val( zero_pad( this.value ) );
		
		var $tabs = $('#tabs').tabs();
		var index = $tabs.tabs('option', 'selected');
		if ($(this).attr('id') == 'h')
			jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find('span#h').html(this.value);
		if ($(this).attr('id') == 'm')
			jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find('span#m').html(this.value);			
		if ($(this).attr('id') == 's')
			jQuery( $('table#bb_submit_table tr.set-row ').get(index) ).find('span#s').html(this.value);
		set_totals();
	});
}
/* 
*  zero pad an integer to 2 places
*
*/
function zero_pad (number){
	return (number < 10 ? '0' : '') + number;
}
/*
* return true if passed argument is unsigned int
*
*/
function isUnsignedInteger(s) {
	return (s.toString().search(/^[0-9]+$/) == 0);
}
/*
* return true if passed argument is an int or decimal
*
*/
function isDecimal(s) {
	return (s.toString().search('^[0-9]*\u002E?[0-9]+$') == 0);
}
/*
 * validate the set submitted
 *
*/
function submit_set_validate(){
	var msg = '', tab='',duration = '';
	msg += ( undefined != $('input.sport').val() )? '' : '<p>You must add an effort to proceed.</p>' ;
		
	$('select.setting').each( function(index){
		tab += ('' == this.value)? (index+1)+' ' : '' ;
	});
		msg += ('' == tab)? '' : '<p>Select a setting for your effort.  '+'tabs:&nbsp;&nbsp;'+tab+'</p>' ;	
		tab = '';	

	$('strong.duration').each( function(index){
		var duration = '';
		$(this).find('input.duration').each(function(index){
			duration += isUnsignedInteger(this.value) ? ''  : (index+1);
		});
		tab += '' == duration ? ''  : (index+1)+' ' ;
	});
		msg += ('' == tab)? '' : '<p>Enter a value for duration.  '+'tabs:&nbsp;&nbsp;'+tab+'</p>' ;
		tab = '';

	$('input.distance').each( function(index){
		tab += isDecimal(this.value) ? ''  : (index+1)+' ' ;
	});
		msg += ('' == tab)? '' : '<p>Enter a value for distance.  '+'tabs:&nbsp;&nbsp;'+tab+'</p>' ;
		tab = '';

	$('input.difficulty').each( function(index){
		tab += isUnsignedInteger(this.value) ? ''  : (index+1)+' ' ;
	});
		msg += ('' == tab)? '' : '<p>Enter a value for difficulty.  '+'tabs:&nbsp;&nbsp;'+tab+'</p>' ;
		tab = '';

	$('input.water').each( function(index){
		tab += isDecimal(this.value) ? ''  : (index+1)+' ' ;
	});
		msg += ('' == tab)? '' : '<p>Enter a value for water.  '+'tabs:&nbsp;&nbsp;'+tab+'</p>' ;
		tab = '';

	if (msg != ''){
		$( "#tabs").tabs('select', '#' + 'tabs-1');//select new tab
		$('div.bb-submit#bb_update').html('<div class="error" align="center"><p><strong>'+msg+'</strong></p></div>');
		$('.updated').hide();
		$('.updated').fadeIn('slow');
		return false;
	}
	$( "#tabs").tabs('select', '#' + 'tabs-1');//select new tab
	return true;
}

jQuery(document).ready(function($){	
	//GENERAL MENU FUNCTIONS
	bb_datepicker('.bb-datepicker');
	
});






