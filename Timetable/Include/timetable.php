<div class="timetable">
	<table>
    <tr>
        <th>Time</th>
        <th>Monday</th>
        <th>Tuesday</th>
        <th>Wednesday</th>
        <th>Thursday</th>
        <th>Friday</th>
    </tr>

    <?php
    require_once '/php/databaseAPI.php';
    require_once '/php/SqlObject.php';
    //amazonPush();
    //amazonPull();
$lq = "SELECT stu_name_first, stu_name_last, shift_id, shi_day, shi_time, str_shortname, str_name FROM STIMulate.facilitators JOIN STIMulate.shifts ON facilitators.student_id=shifts.student_id JOIN STIMulate.streams ON shifts.shi_stream=streams.stream_id WHERE facilitators.active = 1 ORDER BY shift_id asc";
    $sqlObject = new \php\SqlObject($lq); // WHERE shifts.student_id = :student_id", array(8571091)); // HARD CODED
    $shiftInformation = $sqlObject->Execute();
    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.volunteerabsenties WHERE volunteerabsenties.volunteerID = :volunteerID", array(8276617)); // HARD CODED
    $absentInformation = $sqlObject->Execute();

    $DAYS_IN_WEEK = 5;
    $START_HOUR = '08:00:00'; // Must be before 12 noon, whole hour only (0-12), set to hour before earliest shift. // CHANGE THIS TO BE A QUERY
    $SECTIONS_IN_DAY = 8;

    $row = 1;

    for($i = 0; $i < $SECTIONS_IN_DAY; $i++){
        $currentTime = date("h:i", strtotime('+'.($row*60).'minutes', strtotime($START_HOUR)));
        echo '<tr>';
        echo '<td class="column-time">'.$currentTime.'</td>';
        for($j = 0; $j < $DAYS_IN_WEEK; $j++){
            echo "<td>";
            InsertActivity($shiftInformation, $absentInformation, $j, $currentTime);
            echo "</td>";
        }
        echo '</tr>';
        $row++;
    }

    function InsertActivity($shiftInformation, $absentInformation, $day, $currentTime){
        $dayArray = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
        $currentDayWords = $dayArray[$day];
        $returned = false;
        for($i = 0; $i < count($shiftInformation); $i++){
            if($shiftInformation[$i]['shi_day'] == $day) {
                if($currentTime == $shiftInformation[$i]['shi_time'])
                {
                    NameCard($shiftInformation[$i], $absentInformation);
                }
                //$hours = explode('.', $shiftInformation[$i]['duration'])[0];
                //$seconds = explode('.', $shiftInformation[$i]['duration'])[1];
                //$maxTime = date("H:i:s", strtotime('+'.$hours.' hours '.$seconds.' seconds', strtotime($shiftInformation[$i]['time'])));
                //$volunteerTime = date("H:i:s", strtotime($shiftInformation[$i]['time']));

                // if($currentTime >= $volunteerTime && $currentTime < $maxTime){
                //     echo '<td class = "shift"></td>';
                //     $returned = true;
                // }
            }
        }
    }
    function NameCard($f, $absentInformation)
    {
        echo "<span class='namecard' id='".$f["str_shortname"]."'>";
        echo "<span class='".$f['str_shortname']."'>".$f["str_shortname"]."</span>";
        echo $f['stu_name_first']." ".substr($f['stu_name_last'], 0,1);
        echo "</span>";


    }

?>
</table>