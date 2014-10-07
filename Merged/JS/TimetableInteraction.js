$(document).ready(function(){
    getCellData();
});

function getCellData() {
	$('.submit').on('click', function() {

		$('td').each(function(){

			var DAYS = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY"];
			var COLOURS = ["GREEN", "YELLOW", "RED", "WHITE"];

			var array = Create2DArray(5,8);
			var classNames;
			var time;

			for (var i = 0; i < DAYS.length; i++) {

				if ($(this).hasClass(DAYS[i])){
					if ($(this).hasClass("GREEN")){
						//this works now =D to get the time... now how to 
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9; 
						//add to list
						array[i][time] = 27;

						// console.log(DAYS[i]);
						// console.log(classNames[0]);
						// console.log(array[i][time]);

					} else if ($(this).hasClass("YELLOW")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9;  
						array[i][time] = 9;

						// console.log(DAYS[i]);
						// console.log(classNames[0]);
						// console.log(array[i][time]);


					} else if ($(this).hasClass("RED")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9; 
						array[i][time] = 1;

						// console.log(DAYS[i]);
						// console.log(classNames[0]);
						// console.log(array[i][time]);
		
					} else if ($(this).hasClass("WHITE")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9; 
						array[i][time] = -50;

						// console.log(DAYS[i]);
						// console.log(classNames[0]);
						// console.log(array[i][time]);
						
					}

					
				}
			};

		});
	});
}



function Create2DArray(rows,columns) {
   var x = new Array(rows);
   for (var i = 0; i < rows; i++) {
       x[i] = new Array(columns);
   }
   return x;
}