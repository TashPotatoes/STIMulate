<?php
/**
 * Created: Pearl Gariano
 * Edited: Natasha Moffat
 * Date: 19/10/2014
 * Time: 3:10 PM
 */


require_once 'PHP/DatabaseAPI.php';

var_dump($_POST);
?>


<div class="push-right pageWrapper">

    <div class="contentWrapper">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Timetable Preferences</h2>
        </div>
        <div class = "timetableWrapper">

			 <form  id="preferences" name="preferences" action="Global_Timetable_Preferences.php" method="post"> 
				
				<input type="hidden" name="action" value="updatePreferences">
				
				<div>
					<p>Please select your stream:</p>
						<ul>
								<li><input type="radio" name="stream" id="stream-it"value="it"><label for="stream-it">IT</label><br/></li>
								<li><input type="radio" name="stream" id="stream-ma"value="ma"><label for="stream-ma">Maths</label><br/></li>
								<li><input type="radio" name="stream" id="stream-sc"value="sc"><label for="stream-sc">Science</label><br/></li>
								<li><input type="radio" name="stream" id="stream-dh"value="dh"><label for="stream-dh">Duty Host</label><br/></li>
						</ul>
					<p>To enter preferences, click on the cells until they change to the correct colour.</p>
				</div>
		
				<div class="timetable">
					<table  class = "preferences">
						<tr>
							<th>Time</th>
							<th>Monday</th>
							<th>Tuesday</th>
							<th>Wednesday</th>
							<th>Thursday</th>
							<th>Friday</th>
						</tr>

						<?php
						$startOfWeek = date("d-m-Y", strtotime('this week', time()));

						$DAYS_IN_WEEK = 5;
						$START_HOUR = '08:00:00'; // Must be before 12 noon, whole hour only (0-12), set to hour before earliest shift. // CHANGE THIS TO BE A QUERY
						$SHIFTS_IN_DAY = 8;
						$daysArray = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY");
						$row = 1;

						for($shift = 0; $shift < $SHIFTS_IN_DAY; $shift++){
							$currentTime = date("h:i", strtotime('+'.($row*60).'minutes', strtotime($START_HOUR)));
							echo '<tr>';
							echo '<td class="column-time">'.$currentTime.'</td>';
							
							for($day = 0; $day < $DAYS_IN_WEEK; $day++){
								echo "<td class='$shift $daysArray[$day] WHITE'>";
								echo "<input type='hidden' class = 'hiddeninput' name ='$shift $daysArray[$day]' value='-50'>";
								echo "</td>";
							}
							echo '</tr>';
							$row++;
						}
						?>
					</table>
				</div> <!-- End timetable -->

				<p>How many hours would you like to volunteer for your selected stream?<br/>
					<input type="radio" name="max-hour" id="max-hour-1" value="1"><label for="max-hour-1">1</label><br/>
					<input type="radio" name="max-hour" id="max-hour-2" value="2"><label for="max-hour-2">2</label><br/>
					<input type="radio" name="max-hour" id="max-hour-3" value="3"><label for="max-hour-3">3</label><br/>
					<input type="radio" name="max-hour" id="max-hour-4" value="4"><label for="max-hour-4">4</label><br/>
				</p>
				<input id="submit" type="submit" value="Submit">
			</form>
			
		</div> <!-- End timetable wrapper -->		
    </div> <!-- end content wrapper -->
</div> <!-- end push-right-->

<script>
	$( "#preferences" ).submit(function( event ) {
 	
		// retrieve form inputs 		
		var maxHour = document.forms["myForm"]["max-hour"].value;
		var stream = document.forms["myForm"]["stream"].value;
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

</script>