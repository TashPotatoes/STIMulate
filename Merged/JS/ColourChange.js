/**
Author: Pearl Gariano
**/

$(document).ready(function(){
    clickColorEvent();
});

function clickColorEvent() {
	$('td').on('click', function(){
		if($(this).hasClass("GREEN")) {
			$(this).removeClass("GREEN");
			$(this).addClass("YELLOW");
			$(this).css({'background-color': 'yellow'});

		} else if($(this).hasClass("YELLOW")) {
			$(this).removeClass("YELLOW");
			$(this).addClass("RED");
			$(this).css({'background-color': 'red'});

		} else if($(this).hasClass("RED")) {
			$(this).removeClass("RED");
			$(this).css({'background-color': 'white'});

		} else {
			$(this).addClass("GREEN");
			$(this).css({'background-color': 'green'});
		} 
	});
}

