<table class = "timeTable">
	<tr>
		<th>Monday</th>
		<th>Tuesday</th>
		<th>Wednesday</th>
		<th>Thursday</th>
		<th>Friday</th>
		<th>Saturday</th>
		<th>Sunday</th>
	</tr>
	
	<?php
        require_once '/PHP/SqlObject.php';

        $sqlObject = new \PHP\SqlObject("SELECT * FROM stimulate.volunteertimetable WHERE volunteertimetable.volunteerID = :volunteerID", array(8276617)); // HARD CODED
        $volunteerInformation = $sqlObject->Execute();
        $sqlObject = new \PHP\SqlObject("SELECT * FROM stimulate.volunteerabsenties WHERE volunteerabsenties.volunteerID = :volunteerID", array(8276617)); // HARD CODED
        $absentInformation = $sqlObject->Execute();

		// Get first day index
		$startDay = date("w", mktime(0, 0, 0, date('n'), 0, date('y')));

		// Get total number of days in month
		$numberDaysInMonth = date('t');

		// initiating counter and current day
		$currentDay = 1;
		$currentDayCounter = 0;

		// Calendar constants
		$NUM_DAYS_WEEK = 7;
		$WEEKS_IN_MONTH = 6;

		// Creating empty table
		// For each week in a month add in days
		for ($i=0; $i < $WEEKS_IN_MONTH; $i++) { 

			// Stopping unnecessary rows if the days are used and more weeks exist
			if ($currentDay > $numberDaysInMonth) {
				break;
			} else {
                echo '<tr>'.PHP_EOL;
				// If first week start on right day, else add in so long as day isn't greater
				// then current day
				for ($j=0; $j < $NUM_DAYS_WEEK; $j++) { 
					$currentDayWords = date("l", mktime(0, 0, 0, date('n'), $currentDay, date('y')));
                    $currentDateTime = date("Y-m-d", mktime(0, 0, 0, date('n'),$currentDay, date('y')));
                    $currentDateTimeAdjustedUnix = strtotime($currentDateTime);
                    //var_dump($currentDateTime, $currentDateTimeUnix, $i, $j);

					// If first week start right day
					if ($i == 0) {
						// Add in until end of week
						if ($currentDayCounter >= $startDay) {
                            InsertActivities($currentDayWords, $currentDay, $volunteerInformation, $absentInformation, $currentDateTimeAdjustedUnix);
							$currentDay++;
						} else {
							echo '<td></td>'.PHP_EOL;
						}
					// Else not first week
					} else {
						if ($currentDay > $numberDaysInMonth) {
							echo '<td></td>'.PHP_EOL;
						} else {
                            InsertActivities($currentDayWords, $currentDay, $volunteerInformation, $absentInformation, $currentDateTimeAdjustedUnix);
							$currentDay++;
						}
					}

					// Add the timer. Done this way to get out of first week
					$currentDayCounter++;
				}	
			}

			// Row is finished
			echo '</tr>'.PHP_EOL;
		}

    function InsertActivities($currentDayWords, $currentDay, $volunteerInformation, $absentInformation, $currentDateTimeAdjustedUnix){
        echo '<td class = "'.$currentDayWords.' cell"><a>'.$currentDay.'</a>'; // href = "DayTable?day='.$currentDateTimeAdjustedUnix.'"
        $counter = 0;
        for($i = 0; $i < count($volunteerInformation); $i++){
            if($currentDayWords == $volunteerInformation[$i]['day']){
                $notAbsentTime = true;
                for($j = 0; $j < count($absentInformation); $j++) {
                    if(date("Y-m-d", $currentDateTimeAdjustedUnix) == date("Y-m-d", strtotime($absentInformation[$j]["timeStamp"]))){
                        if($volunteerInformation[$i]["time"] == date("H:i:s", strtotime($absentInformation[$j]["timeStamp"]))){
                            $notAbsentTime = false;
                            break;
                        }
                    }
                }
                if($currentDayWords == $volunteerInformation[$i]["day"]&& $counter < 4 && $notAbsentTime){

                    echo '<a class = "activity"><p>'.$volunteerInformation[$i]["time"]."</p><p>".$volunteerInformation[$i]["duration"]." hours".'</p>
                    <input type = "hidden" value = "'.$volunteerInformation[$i]["time"]."d".$currentDay.'" name = "hiddentText">
                    </a>'; //href = "Activity?activity='.$volunteerInformation[$i]["timeID"].'"
                    $counter++;
                }
            }
        }
        echo '</td>'.PHP_EOL;
    }
	?>
</table>