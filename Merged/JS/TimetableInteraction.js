$(document).ready(function(){
    getCellData();
});

function getCellData() {
	$('submit').on('click', function() {

		console.log("clicked");

		$('td').each(function(){

			console.log(1);

			if ($(this).hasClass("MONDAY")){
				//var monday = 
				if ($(this).hasClass("GREEN")){
					//ADD TIME AND 3 TO ARRAY
					var test = $(this).classList[0];
					console.log(test);
				} else if ($(this).hasClass("YELLOW")){
					//ADD TIME AND 2 TO ARRAY

				} else if ($(this).hasClass("RED")){
					//ADD TIME AND 1 TO ARRAY

				}
			}
		});
	});
}

