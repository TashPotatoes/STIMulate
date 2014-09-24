<div class = "push-right">
    <?php if("index.php" == basename($_SERVER['PHP_SELF'])) { ?>
    <div class = "filter">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Filters</h2>
        </div>
        <span class = "filter-stream">
            <ul>
                <li id = "filterStreamIt" class = "stream-filters">IT</li>
                <li id = "filterStreamSc" class = "stream-filters">Science</li>
                <li id = "filterStreamMa" class = "stream-filters">Maths</li>
                <li id = "filterStreamDh" class = "stream-filters">Duty Hosts</li>
            </ul>
        </span>
        <span class = "searchbar">
            <img src="IMG/search.png" class = "inline-image searchIcon">
            <input type="text" placeholder="Search Topics, Streams, or Units..." class = "searchSpan">
        </span>
    </div>
    <?php } ?>

    <div class = "timetableWrapper">

        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Timetable</h2>
        </div>
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
                require_once 'PHP/databaseAPI.php';
                require_once 'PHP/SqlObject.php';

                $lq = "SELECT facilitators.student_id, stu_name_first, stu_name_last, shift_id, shi_day, shi_time, str_shortname, str_name
                FROM STIMulate.facilitators
                JOIN STIMulate.shifts ON facilitators.student_id=shifts.student_id
                JOIN STIMulate.streams ON shifts.shi_stream=streams.stream_id
                WHERE facilitators.active = 1
                ORDER BY shift_id asc;";
                $sqlObject = new \php\SqlObject($lq); // WHERE shifts.student_id = :student_id", array(8571091)); // HARD CODED
                $shiftInformation = $sqlObject->Execute();

                $absentInformation = RetrieveAbsentRecords();

                $startOfWeek = date("d-m-Y", strtotime('this week', time()));

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
                    for($i = 0; $i < count($shiftInformation); $i++){
                        if($shiftInformation[$i]['shi_day'] == $day) {
                            if($currentTime == $shiftInformation[$i]['shi_time'])
                            {
                                if(count($absentInformation) > 0) {
                                    $inputtedNameCards = 0;
                                    for($j = 0; $j < count($absentInformation); $j++){
                                        $absentDay = date("w", strtotime($absentInformation[$j]["absence_timestamp"]))-1;
                                        $absentTime = date("H:i", strtotime($absentInformation[$j]["absence_timestamp"]));

                                        if($shiftInformation[$i]["shi_time"] == $absentTime && $day == $absentDay &&
                                            $absentInformation[$j]["volunteer_id"] == $shiftInformation[$i]["student_id"]){
                                            NameCard($shiftInformation[$i], $day, true);
                                            $inputtedNameCards++;
                                            break;
                                        }
                                    }
                                    if($inputtedNameCards == 0) {
                                        NameCard($shiftInformation[$i], $day, false);
                                    }
                                } else {
                                    NameCard($shiftInformation[$i], $day, false);
                                }


                            }
                        }
                    }
                }
                function NameCard($shiftInformation, $day, $disabled)
                {
                    if($disabled){
                        echo "<div class='absent namecard f-".$shiftInformation["str_shortname"]." day".$day." time".$shiftInformation["shi_time"]." id".$shiftInformation["student_id"]."'>";
                    } else {
                        echo "<div class='namecard f-".$shiftInformation["str_shortname"]." day".$day." time".$shiftInformation["shi_time"]." id".$shiftInformation["student_id"]."'>";
                    }
                    echo "<span class='".$shiftInformation['str_shortname']."'>".$shiftInformation["str_shortname"]."</span>";
                    echo $shiftInformation['stu_name_first']." ".substr($shiftInformation['stu_name_last'], 0,1);
                    echo "</div>";
                }

                function RetrieveAbsentRecords(){
                    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.absences WHERE active = 1;");
                    $absentInformation = $sqlObject->Execute();

                    $dateNow = date("d", strtotime("now"));
                    for($i = 0; $i < count($absentInformation); $i++) {
                        $absentDate =  date("d", strtotime($absentInformation[$i]["absence_timestamp"]));

                        if($dateNow + 7 < $absentDate) {
                            DisableAbsentRecord($absentInformation[$i]["absence_id"]);
                        }
                    }

                    return $sqlObject->Execute();
                }

                function DisableAbsentRecord($absentID){
                    $sqlObject = new \PHP\SqlObject("UPDATE STIMulate.absences SET active = 0 WHERE absence_id = :absence_id;", array($absentID));
                    $sqlObject->Execute();
                }

                ?>
            </table>
        </div>
    </div>
</div>