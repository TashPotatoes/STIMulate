$(document).ready(function(){
    getCellData();
});

function getCellData() {
	$('.submit').on('click', function() {

		$('td').each(function(){

			var DAYS = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY"];
			var COLOURS = ["GREEN", "YELLOW", "RED"];

			var array = [[]];
			var classNames;
			var time;

			for (var i = 0; i <= 4; i++) {
			
				if ($(this).hasClass(DAYS[i])){
					//console.log(DAYS[i]);

					if ($(this).hasClass("GREEN")){
						//this works now =D to get the time... now how to 
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]; 
						//add to list
						array[i][time] = 27;

						console.log(array[i][time]);
						console.log(DAYS[i]);
						console.log(classNames[0]);

					} else if ($(this).hasClass("YELLOW")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]; 
						array[i][time] = 9;

						console.log(array[i][time]);
						console.log(DAYS[i]);
						console.log(classNames[0]);


					} else if ($(this).hasClass("RED")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]; 
						array[i][time] = 1;

						console.log(array[i][time]);
						console.log(DAYS[i]);
						console.log(classNames[0]);
		
					} else {
						console.log("not selected");
						
						//classNames = $(this).attr('class').split(' ');
						//time = classNames[0]; 
						//array[i][time] = -50;

						//console.log(array[i][time]);
						//console.log(DAYS[i]);
						//console.log(classNames[0]);
					}

				}

			};//end for loop

			//test for the rest of the days

		});
	});
}

