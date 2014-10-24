<!--
Author: Pearl Gariano
-->
<?php
    var_dump($_POST);


?>
<div class="push-right pageWrapper">

    <div class="contentWrapper">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Timetable Preferences</h2>
        </div>
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
		
    <div class = "timetableWrapper">

		 <form  name="preferences" action="Global_Timetable_Preferences.php" method="post"> 
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
		
    </div> <!-- end content wrapper -->
</div> <!-- end push-right-->


<script>
	function validateForm() {
		var maxHour = document.forms["myForm"]["max-hour"].value;
		var stream = document.forms["myForm"]["stream"].value;
		var table = document.getElementById('preferences');
		var tds= array();
//		var valueArray = array ( "", "1st", "2nd", "3rd");
		for ( var i = 0; i < table.length; i++){
			tds[i] = table.getElementsByTagName('td');
		}
		
		
		console.log("here");
		 $.ajax({
			url: "Global_Timetable_Preferences.php,
			type: "POST",
			data: {
				stream: stream , maxhour: maxHour, prefs: tds
			},
			cache: false,
			success: function (output) {
				console.log("This is a ajax succes" + output);
			},
			error: function () {
				console.log("error");
			}
	}

</script>


<?php
if ( isset($_POST['stream'])){
	echo "inside post";
	
	$sql = "INSERT INTO `preferences`(`user_id`, `stream`, `day`, `9`, `10`, `11`, `12`, `1`, `2`, `3`, `4`) VALUES ";
	$user_ID= $_SESSION['user_id'];
	$values = array ( "3rd", "2nd", "1st");
	$prefArray = array();
	
	if ($user_ID[0] == "n") {
		$stream = $_POST['stream'];
		$tds = $_POST['prefs'];
		for ($i = 0; $i < count($tds); $i++){
			$curDay = $i % 5;
			if ($i % 6 == 0) {
				//Ignore first column since it only holds a time
				$i++;
			}
			
			//blablabla need it in rows so this is wrong. 
			else if ($tds[$i]  != "") {
				$pref = array_search($tds[$i], $values);
				switch ($pref) {
					case "1st":
						$prefArray[$i] = 3*3*3;
						break;
					case "2nd":
						echo "";
						break;
					case "3rd":
						echo "";
						break;
					default:
						echo "Warning: An error has occurred";
				}

				//TODO generate SQL string
				//TODO Add sql classes and execute sql
				//TODO make sure it all works
			}
			
		}
		
	}
	
	
	/*	$rows = $html->find('tr');
		foreach($rows as $row) {
			echo $row->plaintext;
		}

		 $num = null;
		 foreach($tds as $td){

			 if(($td->plaintext == '') && ()){
				
				$next_td = $td->next_sibling();
				$num = $next_td->plaintext ;  
	*/
}