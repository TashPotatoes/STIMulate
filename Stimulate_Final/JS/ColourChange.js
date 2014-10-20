/**
Author: Pearl Gariano
**/

$(document).ready(function(){
    clickColorEvent();

});

function clickColorEvent() {
	$('td').on('click', function(){
		if ($(this).hasClass("GREEN")) {
			$(this).removeClass("GREEN");
			$(this).addClass("YELLOW");
			$(this).css({'background-color': 'rgb(75,75,230)'});
			$(this).html("2nd");

		} else if ($(this).hasClass("YELLOW")) {
			$(this).removeClass("YELLOW");
			$(this).addClass("RED");
			$(this).css({'background-color': 'rgb(50,50,190)'});
			$(this).html("3rd");
		} else if ($(this).hasClass("RED")) {
			$(this).removeClass("RED");
			$(this).addClass("WHITE");
			$(this).css({'background-color': 'rgb(255,255,255)'});
			$(this).html("");
		} else if ($(this).hasClass("WHITE")){
			$(this).removeClass("WHITE");
			$(this).addClass("GREEN");
			$(this).css({'background-color': 'rgb(50,50,1900)'});
			$(this).html("1st");

		} 
	});
}

