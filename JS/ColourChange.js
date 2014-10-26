$(document).ready(function(){
    clickColorEvent();

});
/* Function to visually alter the volunteer hours data input */
function clickColorEvent() {
	$('td').on('click', function(){

		if ($(this).hasClass("GREEN")) {
			$(this).removeClass("GREEN");
			$(this).addClass("YELLOW");
			$(this).css({'background-color': '#438EB9'});
			$(this).children('p').html("2nd");
			$(this).children(".hiddeninput").val(9);

		} else if ($(this).hasClass("YELLOW")) {
			$(this).removeClass("YELLOW");
			$(this).addClass("RED");
			$(this).css({'background-color': '#28556F'});
			$(this).children('p').html("1st");
			$(this).children(".hiddeninput").val(27);

		} else if ($(this).hasClass("RED")) {
			$(this).removeClass("RED");
			$(this).addClass("WHITE");
			$(this).css({'background-color': 'rgb(255,255,255)'});
			$(this).children('p').html(" ");
			$(this).children(".hiddeninput").val(-50);

		} else if ($(this).hasClass("WHITE")){
			$(this).removeClass("WHITE");
			$(this).addClass("GREEN");
			$(this).css({'background-color': '#8EBBD5'});
			$(this).children('p').html("3rd");
			$(this).children(".hiddeninput").val(3);
		} 
	});
}

