$(document).ready(function(){
    getCellData();
});

function getCellData() {
	$('.submit').on('click', function() {

		console.log("clicked");

		$('td').each(function(){

			//var DAYS = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY"];
			//var COLOURS = ["GREEN", "YELLOW", "RED"];

			if ($(this).hasClass("MONDAY")){

				
				if ($(this).hasClass("GREEN")){

					//this works now =D to get the time... now how to 
					var classNames = $(this).attr('class').split(' ');
					var time = classNames[0]; 

					console.log("test: monday green");
					console.log(classNames[0]);

				} else if ($(this).hasClass("YELLOW")){
					//get time and change yellow to 2

				} else if ($(this).hasClass("RED")){
					//get time and change green to 1

				}
			}

			//test for the rest of the days

		});
	});
}

