$(document).ready(function(){
    getCellData();
});

function getCellData() {
	$('.submit').on('click', function() {

		console.log("clicked");

		$('td').each(function(){

			//var DAYS = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY"]

			if ($(this).hasClass("MONDAY")){
				//var monday = 
				
				if ($(this).hasClass("GREEN")){
					//get time and change green to 3
					console.log("test: monday green");
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

