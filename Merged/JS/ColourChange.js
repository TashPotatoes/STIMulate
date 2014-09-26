/**
Author: Pearl Gariano
**/

$(document).ready(function(){
    clickColorEvent();
});

function clickColorEvent() {
	$('.column-colour').on('click', function(){
		console.log(this);
		if($(this).hasClass("green")) {
			console.log(1);
			$(this).removeClass("green");
			$(this).addClass("yellow");
			$(this).css({'background-color': 'yellow'});

		} else if($(this).hasClass("yellow")) {
			$(this).removeClass("yellow");
			$(this).addClass("red");
			$(this).css({'background-color': 'red'});

		} else if($(this).hasClass("red")) {
			$(this).removeClass("red");
			$(this).css({'background-color': 'white'});

		} else {
			$(this).addClass("green");
			$(this).css({'background-color': 'green'});
		} 
	});
}

