$(document).ready(function(){
    getCellData();
});

function getCellData() {
	$('#submit').on('click', function() {

		var DAYS = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY"];
		var COLOURS = ["GREEN", "YELLOW", "RED", "WHITE"];

		var array = Create2DArray(5,8);
		var classNames;
		var time;

		$('td').each(function(){

			for (var i = 0; i < DAYS.length; i++) {

				if ($(this).hasClass(DAYS[i])){
					if ($(this).hasClass("GREEN")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9; 
						array[i][time] = 27;

						// console.log(DAYS[i]);
						// console.log(classNames[0]);
						// console.log(array[i][time]);

					} else if ($(this).hasClass("YELLOW")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9;  
						array[i][time] = 9;


					} else if ($(this).hasClass("RED")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9; 
						array[i][time] = 1;
		
					} else if ($(this).hasClass("WHITE")){
						
						classNames = $(this).attr('class').split(' ');
						time = classNames[0]-9; 
						array[i][time] = -50;
						
					}	
				}
			};
		});
		
		if ($('input[name=stream]:checked').length > 0) {
    		var stream = $('input[name=stream]:checked').val();
		} else {
			alert("Please select your stream");
		}
		

		if ($('input[name=max-hour]:checked').length > 0) {
			var hours = $('input[name=max-hour]:checked').val();
		} else {
			alert("Please select your max hours");
		}
		
		//ajax code to submit form
		$.ajax({
			url: 'PHP/DatabaseAPI.php',
			data: {
				"action": 'updatePreferences',
				"stream": stream,
				"hours": hours,
				"array": array
			},
			type: 'post',
			datatype: 'JSON',
			success: function(result){
				console.log(result);
			}
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