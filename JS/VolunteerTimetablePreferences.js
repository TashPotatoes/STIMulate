$(document).ready(function(){
		processInput();
});

	
function processInput(){	
	console.log("starting");
	$('#submit').on('click', function() { //$( "#preferences" ).submit(function( event ) {
		console.log("button");

 	
		// validate inputs
		if (!($('input[name=stream]:checked').length > 0)) {
			alert("Please select your stream");
			event.preventDefault();
		}
		if (!($('input[name=max-hour]:checked').length <= 0)) {
			alert("Please select your max hours");
			event.preventDefault();
		}	
		
		
		var result = array();
		
		// iterate over all rows in html tables, If the row has a preference selected then process 
		// that row. Only rows with preferences in them get stored in the database
		
		$('.preferences > tr').each(function(shift, v){
			if $(this.find('td.GREEN', 'td.YELLOW', 'td.RED').length > 0) {
				
				// add the corresponding preference value for the class to the array
				$(this).children('td').each(function(day, vv){
					
					switch ( $(this).attr("class")) {
					case 'WHITE':  //null
						results[day][shift] = -50;
						break;
					case 'GREEN': //3rd
						results[day][shift] = 3;
						break;
					case 'YELLOW': //2nd
						results[day][shift] = 9;
						break;
					case 'RED': //1st
						results[day][shift] = 27;
						break;						
					}
				});
			}
		});
		
		// post the variables to the page
		console.log("here");
		 $.ajax({
			url: "Global_Timetable_Preferences.php,
			type: "POST",
			data: {
				stream: stream , maxhour: maxHour, prefs: results
			},
			cache: false,
			success: function (output) {
				console.log("This is a ajax succes" + output);
			},
			error: function () {
				console.log("error");
			}
		});
	});
}

