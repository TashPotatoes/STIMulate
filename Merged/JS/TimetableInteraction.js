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
		
		//var studentNumber = session things
		var stream = $("#stream").val();
		var hours = $("#hours").val();

		//put all data in data string...
		var dataString = 'stream='+ stream + 'hours='+hours + 'preferences='+array;
		
		if( stream=="default" || hours=="default"){
			alert("Please ensure both the dropdown boxes are filled out correctly.");
		} else {
		
			//ajax code to submit form
			$.ajax({
				type: "POST",
				url: "../Merged/SendPreferences.php",
				data: dataString,
				cache: false,
				//success: function(result){
					//alert(result);
				//}
			});
		}

	});
}



function Create2DArray(rows,columns) {
   var x = new Array(rows);
   for (var i = 0; i < rows; i++) {
       x[i] = new Array(columns);
   }
   return x;
}