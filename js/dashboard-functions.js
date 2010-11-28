function addLoadEvent(func){
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {window.onload = func;}
	else {window.onload = function(){oldonload();func();}}
}
//addLoadEvent(bb_menu_scripts);
//bb_dash_scripts();
//function bb_menu_scripts(){
loadTime();//load clock timer
jQuery(document).ready(function($){	
	
		//GENERAL MENU FUNCTIONS
		bb_datepicker('.bb-datepicker');
		
		//PROGRAM FUNCTIONS
		stylize_status('.status');
		stylize_difficulty('.difficulty');
		linked_row('.bb-program#bb_program_table tr.set-row');
		
		//AUTHOR FUNCTIONS
		efforts('#tabs');
		
		$('#thisbuttoncunt').click(function(){
			var page = $('a#linkme').attr('href');
			var mydata =$(':input').serializeArray();
			$.ajax({
			   type: "POST",
			dataType: "html",
			   url: page,
			   data: mydata,
			   success: function(msg){
			     alert( "Data Saved: " + msg );
			   }
			});
		});
});
//}
//form validator

function validateForms(){
	$('form').each(function(index) {
		elementsForms[index].onsubmit = function (){	return validate_num(this);}
	});
}
//insert datepickers
function bb_datepicker(elms){
	$(elms).datepicker({dateFormat: 'D dd-M-yy'});
}
//create accordion
function bb_accordion(elms){
	var icons = {
		header: "ui-icon-circle-arrow-e",
		headerSelected: "ui-icon-circle-arrow-s"
	};
	$(elms).accordion({
		icons: icons
	});
	$( "#toggle" ).button().toggle(function() {
		$(elms).accordion( "option", "icons", false );
	}, function() {
		$(elms).accordion( "option", "icons", icons );
	});
}
//insert sliders
function bb_slider(elms,min,max,step,init,width){
	$('td.new-effort ' + elms).focus(function() {$(this).val('');}); //clear input on focus
	var $element = $('td.new-effort ' + elms);
	var slider = $("<div id='slider'></div>").insertAfter( $($element).last() ).slider({
		animate: true,
		min: min,
		max: max,
		step: step,
		range: "min",
		value: init,
		slide: function( event, ui ) {
			var $tabs = $('#tabs').tabs();
			var index = $tabs.tabs('option', 'selected');
			$($element.get(index-1)).val(ui.value);
			//also change values of row results
			//format for meteres - kilometers
			if('	Swimming	' ==  $( $('table#bb_author_table tr.set-row td.discipline').get(index-1) ).html() && '.distance' == elms ){
				jQuery( $('table#bb_author_table tr.set-row ').get(index) ).find(elms).html(ui.value/1000);
			} else {
				jQuery( $('table#bb_author_table tr.set-row ').get(index) ).find(elms).html(ui.value);
			}
			set_totals();//update totals in set-row
		}
	}).width(width);
	$element.change(function() {
		slider.slider( "value", this.value);
		
		//also change values of row results
		var $tabs = $('#tabs').tabs();
		var index = $tabs.tabs('option', 'selected');
		//format for meteres - kilometers
		if('	Swimming	' == $($('table#bb_author_table tr.set-row td.discipline').get(index-1)).html() && '.distance' == elms ){
			jQuery( $('table#bb_author_table tr.set-row ').get(index) ).find(elms).html(this.value/1000);
		} else {
			jQuery( $('table#bb_author_table tr.set-row ').get(index) ).find(elms).html(this.value);						
		}
		set_totals();//update totals in set-row
	});
}
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
//clock functions
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
function checkTime(i){if (i<10){  i="0" + i; }; return i;}
// stylize the status box
function stylize_status(elm){
	$(elm).each(function(index) {
		switch ( $(this).text() ) {
			case 'incomplete':	$(this).css({'color': 'hsla( 0, 100%, 50% , 0.9 )'});
			break;
			case 'complete': 	$(this).css({'color': 'hsla( 150, 100%, 30% , 0.9 )'});
			break;
			case 'partial': 	$(this).css({'color': 'hsla( 30, 100%, 40% , 0.8 )'});
			break;
		}
	});
}
//stylize the difficulty
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
//highlight entire row on mouseover in table
function linked_row(elm){
	$(elm).click(function () {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
	});
}

/*
 *EFFORTS - ALLOWS USER TO ADD NEW EFFORTS INTO THE JQUERY TAB STRUCTURE AND 
 * FILL THEM WITH THEIR EXERCISE DATA.  DATA IS THEN SUBMITTED TO BOOMBOOKS DATABASE
 *
 *CAN ACCEPT A REFERRED SET FROM PROGRAM WHICH HAS LESS FREEDOM
 */
function efforts(elm){
	var $discipline = $( ".new-effort#discipline");
	var tab_counter = 2;
	// tabs init with a custom tab template and an "add" callback filling in the content
	var $tabs = $( "#tabs").tabs({
		tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>",
		add: function( event, ui ) {
			var table = add_effort_editor(tab_counter,$discipline.val());
			$( ui.panel ).append( "<p>" + table + "</p>" );
				switch($discipline.val()){
				case 'Cycling' :
					bb_slider('.duration',1,300,1,0,250);//#[elms],min,max,step,init,width
					bb_slider('.distance',1,300,1,0,250);
				break;
				case 'Swimming' :
					bb_slider('.duration',1,300,1,0,250);//#[elms],min,max,step,init,width
					bb_slider('.distance',25,100*100,50,0,250);
				break;
				case 'Running' :
					bb_slider('.duration',1,4*60,1,0,250);//#[elms],min,max,step,init,width
					bb_slider('.distance',1,50,1,0,250);
				break;
			}
			bb_slider('.difficulty',1,10,1,0,250);
			bb_slider('.water',1,6,.1,0,250);
			add_set_row($discipline.val());//add row to set totals
			update_location('.setting');//update location/setting on change
			$tabs.tabs('select', '#' + ui.panel.id);//select new tab
		}
	});
	// actual addTab function: adds new tab using the title input from the discipline select element
	function addTab() {
		if( '' != $discipline.val()){
			$tabs.tabs( "add", "#tabs-" + tab_counter, $discipline.val() );
			tab_counter++;
			$('div.bb-author#bb_update').slideUp('slow','swing');
		}else{
			$('div.bb-author#bb_update').html('<div class="updated" align="center"><p><strong>Select a discipline before adding a new effort.</strong></p></div>');
		}
	}

	// addTab button: adds a new effort tab with discipline title and table input contents
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
		jQuery($('table#bb_author_table tr.set-row').get(index)).remove();
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
function add_set_row(discipline) {
$lastrow = $('table#bb_author_table tr.set-row').last();
	$('<tr class="bb-author set-row">'+
		'<td class="bb-author discipline">	'+discipline+'	</td>'+
		'<td class="bb-author setting">		-		</td>'+
		'<td class="bb-author distance">	-		</td>'+
		'<td class="bb-author duration">	-		</td>'+
		'<td class="bb-author difficulty">	-		</td>'+
		'<td class="bb-author water">	-		</td>'+
	'</tr>').insertAfter($lastrow);
	return ;	
}
/*
 *
 * Template for effort editor
 *
 *
 */
function add_effort_editor(tab_counter,discipline) {
	var dist_units = ( 'Swimming' == discipline)? 'meters' : 'kilometers';
	var table = '<table class="bb-author new-effort" id="author_form_table"><tr><td class="bb-author new-effort col-1" >'+
		'<select name="'+tab_counter+'setting" value="$effort=>setting" class="bb-author new-effort setting" id="setting" ><option value="">Select location...</option><option value="road">road</option><option value="gym">gym</option><option value="pool">pool</option><option value="open_water">open water</option><option value="wind_trainer">wind trainer</option></select>'+
		'<input type="text" name="'+tab_counter+'foods" size="100" value="foods consumed" autocomplete="on" class="bb-author new-effort food" id="food" />'+
		'</td><td class="bb-author new-effort col-2" ><strong>Time</strong><input type="text" name="'+tab_counter+'duration" autocomplete="on" value="minutes" class="bb-author new-effort duration" id="duration" /></td></tr>'+
		'<tr><td rowspan="3" class="bb-author new-effort col-1" ><strong>Describe the session</strong><br /><textarea name="'+tab_counter+'details" class="bb-author new-effort details" id="details" ></textarea>'+
		'</td><td class="bb-author new-effort col-2"><strong>Distance</strong>'+
		'<input type="text" name="'+tab_counter+'distance" autocomplete="on" value="'+dist_units+'" class="bb-author new-effort distance" id="distance" />'+
		'</td></tr><tr><td class="bb-author new-effort col-2"><strong>Difficulty</strong><input type="text" name="'+tab_counter+'difficulty" autocomplete="on" value="out of 10" class="bb-author new-effort difficulty" id="difficulty" />'+
		'</td></tr><tr><td class="bb-author new-effort col-2"><strong>Water</strong><input type="text" name="'+tab_counter+'water" autocomplete="on" value="litres" class="bb-author new-effort water" id="water" /></td></tr></table>';
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
	var distance = 0 , duration = 0 , difficulty = 0, avg_index = 0 , water = 0;
	
	//total distance if returns int
	$('table#bb_author_table tr.set-row td.distance').each(function(index,elm) {
		if( !isNaN( parseInt( $(elm).html() ) ) ){
	    	distance += parseFloat( $(elm).html() );
		}

	});
	$('table#bb_author_table tr.total-row td.distance').html(distance + ' kms');
	
	//total durations if returns int
	$('table#bb_author_table tr.set-row td.duration').each(function(index,elm) {
		if( !isNaN( parseInt( $(elm).html() ) ) ){
	    	duration += parseInt( $(elm).html() );
		}
	});
	$('table#bb_author_table tr.total-row td.duration').html(duration + ' mins');
	
	//average difficulty if returns int
	$('table#bb_author_table tr.set-row td.difficulty').each(function(index,elm) {
		if( !isNaN( parseInt( $(elm).html() ) ) ){
	    	difficulty += parseInt( $(elm).html() );
			avg_index++;
		}
	});
	if(0 != avg_index){
		difficulty = difficulty/avg_index;
	}
	$('table#bb_author_table tr.total-row td.difficulty').html( 'avg ' + difficulty.toFixed(1));
	
	//total water if returns int
	$('table#bb_author_table tr.set-row td.water').each(function(index,elm) {
		if( !isNaN( parseFloat( $(elm).html() ) ) ){
	    	water += parseFloat( $(elm).html() );
		}
	});
	$('table#bb_author_table tr.total-row td.water').html(water.toFixed(1) + ' L');
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
			jQuery( $('table#bb_author_table tr.set-row ').get(index) ).find(elm).html(str);
		}
	}).change();
}


// add elements for new set
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