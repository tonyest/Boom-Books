jQuery(document).ready(function($){	
	//GENERAL MENU FUNCTIONS
	bb_datepicker('.bb-datepicker');
	bb_accordion('div#accordion');
	
	//PROGRAM FUNCTIONS
	stylize_status('.status');
	stylize_difficulty('.difficulty');
//	linked_row('.bb-program#bb_program_table tr.set-row-incomplete');
	
	//REPorT FUNCTIONS
	report();
	stretch_map();
});

function bb_datepicker(elms){
	$(elms).datepicker({dateFormat: 'D dd-M-yy'});
}

/*
 *
 * stylize the status box
 *
 *
 */
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
 *
 * highlight entire row on mouseover in table
 *
 *
 */
function linked_row(elm){
	$(elm).click(function () {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
	});
}


function report(){
	$( "#tabs" ).tabs();
	$('td.details > a.toggle-text').each( function () {
		$(this).parent().find('p').hide();
	});
	//toggle details

	$('td.details > a.toggle-text').toggle( function () {	
		$($(this).parent().find('a:contains("hide details")')).text('show details');		
		$($(this).parent().find('a:contains("show details")')).text('hide details');
	
		$(this).parent().find('p').slideToggle();		
	}, function () {
		$($(this).parent().find('a:contains("show details")')).text('hide details');
		$($(this).parent().find('a:contains("hide details")')).text('show details');
		$(this).parent().find('p').slideToggle();		
	});

	//toggle all details
	$('a.toggle-text').toggle(function (){
		$(this).text('hide all');
		$('td.details > a.toggle-text').each( function () {
			$(this).parent().find('p').show();
			$(this).text('hide details');
		});
	} , function () {
		$(this).text('show all');
		$('td.details > a.toggle-text').each( function () {
			$(this).parent().find('p').hide();
			$(this).text('show details');	
		});
	});
}

function stretch_map() {
	
	$('area').click( function () {
		var muscle = $(this).attr('title');
		if ( $('ul.stretches li').hasClass(muscle) ){
		//	$('ul.stretches li input').spinner("destroy");
			 $('ul.stretches li.'+muscle).detach ();
		//	$('ul.stretches li input').spinner();
		} else {
			$('ul.stretches').append('<li class="'+muscle+'" style="float:right;">'+time_spinner(muscle)+'</li>');
			/*$('input.'+muscle).spinner({
				min: 0,
				max: 60,
				step: 1,
				largeStep: 1,
				places: 0,
				suffix: 'mins',
				showOn: 'always',
				increment: 1
			});*/
		}
	});	
}

function time_spinner(arg) {
	return '<label for="'+arg+'" style="float:left">'+arg+' </label><input type="text" size="10" class="'+arg+'" />';
}

/*
 *
 * create accordion
 *
 *
 */
function bb_accordion(elms){
	var icons = {
		header: "ui-icon-circle-arrow-e",
		headerSelected: "ui-icon-circle-arrow-s"
	};
	$(elms).accordion({
		icons: icons,
		autoHeight: true,
		navigation: true,
		fillSpace: true
	});
	$( "#toggle" ).button().toggle(function() {
		$(elms).accordion( "option", "icons", false );
	}, function() {
		$(elms).accordion( "option", "icons", icons );
	});
	
	$('.pc_distance').each( function () {
		$(this).progressbar({ value: parseInt($(this).attr('title')) });
	});
	$('.pc_duration').each( function () {
		$(this).progressbar({ value: parseInt($(this).attr('title')) });
	});
}



