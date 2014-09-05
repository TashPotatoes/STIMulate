<table class = "timeTable">
    <tr>
        <th>Time</th>
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

    $DAYS_IN_WEEK = 7;
    $SECTIONS_IN_DAY = 25; // 24 hours in 30 minute intervals
    $row = 1;

    for($i = 0; $i < $SECTIONS_IN_DAY; $i++){
        echo '<tr>';
        echo '<td>'.$currentTime = date("H:i", strtotime('+'.($row*30).'minutes', strtotime('06:30:00'))).'</td>';
        for($j = 0; $j < $DAYS_IN_WEEK; $j++){
            InsertActivity($volunteerInformation, $absentInformation, $j, $currentTime);
        }
        echo '</tr>';
        $row++;
    }

    function InsertActivity($volunteerInformation, $absentInformation, $day, $currentTime){
        $dayArray = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $currentDayWords = $dayArray[$day];
        $returned = false;
        for($i = 0; $i < count($volunteerInformation); $i++){
            if($volunteerInformation[$i]['day'] == $currentDayWords) {
                $hours = explode('.', $volunteerInformation[$i]['duration'])[0];
                $seconds = explode('.', $volunteerInformation[$i]['duration'])[1];
                $maxTime = date("H:i:s", strtotime('+'.$hours.' hours '.$seconds.' seconds', strtotime($volunteerInformation[$i]['time'])));
                $volunteerTime = date("H:i:s", strtotime($volunteerInformation[$i]['time']));

                if($currentTime >= $volunteerTime && $currentTime < $maxTime){
                    echo '<td class = "shift"></td>';
                    $returned = true;
                }
            }
        }
        if(!$returned){
            echo '<td></td>';
        }
    }

?>
</table>