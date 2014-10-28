<?php
/**
 * Created: Pearl Gariano
 * Edited: Natasha Moffat
 * Date: 19/10/2014
 * Time: 3:10 PM
 */


require_once 'PHP/SqlObject.php';

if(isset($_POST)){
    if(count($_POST) > 0) {
        HandlePost();
    }
}
?>

<style>
    .timetableWrapper {
        background-color: #ffffff;
    }

    .timetableWrapper ul{
        list-style: none;
        padding-left: 10px;
    }

    .timetableWrapper p {
        padding-left: 10px;
    }

    .timetableWrapper input[type = 'submit'] {
        padding: 5px;
        width: 70px;
        height: 30px;
        border: 1px solid rgb(165,165,165);
        background-color: rgb(67,142,185);
        color: #ffffff;
        margin: 10px;
    }
</style>
<div class="push-right">


        <div class = "timetableWrapper">

        <form  id="preferences" name="preferences" action="" method="post"> <!-- Global_Timetable_Preferences.php -->

                <input type="hidden" name="action" value="updatePreferences">

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

            <div class = "headElement">
                <img src="IMG/calander.png" alt="Calander" class = "inline-image">
                <h2 class = "inline-text">Timetable Preferences</h2>
            </div>
				<div class="timetable noselect">
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
                                echo "<p></p>";
								echo '<input type="hidden" class = "hiddeninput" name ="preferenceID[]" value="-50">';
								echo "</td>";
							}
							echo '</tr>';
							$row++;
						}
						?>
					</table>
				</div> <!-- End timetable -->

            <div class = "headElement">
                <img src="IMG/calander.png" alt="Calander" class = "inline-image">
                <h2 class = "inline-text">Hours to Volunteer</h2>
            </div>
				<p>How many hours would you like to volunteer for your selected stream?</p>
            <ul>
                <li><input type="radio" name="max-hour" id="max-hour-1" value="1"><label for="max-hour-1">1</label><br/></li>
                <li><input type="radio" name="max-hour" id="max-hour-2" value="2"><label for="max-hour-2">2</label><br/></li>
                <li><input type="radio" name="max-hour" id="max-hour-3" value="3"><label for="max-hour-3">3</label><br/></li>
                <li><input type="radio" name="max-hour" id="max-hour-4" value="4"><label for="max-hour-4">4</label><br/></li>
            </ul>


				<input id="submit" type="submit" value="Submit">
			</form>
			
		</div> <!-- End timetable wrapper -->		
    </div> <!-- end content wrapper -->

<?php

function HandlePost()
{
    if(isset($_POST['stream'], $_POST['user_id'])) {
        $stream = strtoupper($_POST["stream"]);
        $student = $_SESSION['user_id'];

        $sql = "INSERT INTO STIMulate.preferences (user_id, stream, day, `9`, `10`, `11`, `12`, `1`, `2`, `3`, `4`) VALUES ";

        $SHIFTS_IN_DAY = 8;
        $DAYS_IN_WEEK = 5;
        $startSlice = 0;
        $dataArray = array();
        for ($i = 0; $i < $DAYS_IN_WEEK; $i++) {
            $row = array($student, $stream, $i);
            $counter = $i;
            for ($j = 0; $j < $SHIFTS_IN_DAY; $j++) {
                if ($j == 0) {
                    $sql .= '(:stu_id' . $i . '' . $j . ' , :faculty' . $i . '' . $j . ' , :day' . $i . '' . $j . ', ';
                }
                if ($j + 1 == $SHIFTS_IN_DAY) {
                    $sql .= ":val" . $i . '' . $j . ') ';
                } else {
                    $sql .= ":val" . $i . '' . $j . ', ';
                }

                array_push($row, $_POST['preferenceID'][$counter]);
                $counter += 5;
            }
            if ($i + 1 == $DAYS_IN_WEEK) {
                $sql .= ";";
            } else {
                $sql .= ", ";
            }
            $dataArray = array_merge($dataArray, $row);
            $startSlice += $SHIFTS_IN_DAY;
        }
        $sqlObject = new \PHP\SqlObject($sql, $dataArray);
        $sqlObject->Execute();
    }
}
