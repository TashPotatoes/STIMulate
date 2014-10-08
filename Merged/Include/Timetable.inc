<?php require_once '/php/DatabaseAPI.php'; ?>
    <?php
        if("index.php" == basename($_SERVER['PHP_SELF'])) { 
        $specialisationObject = retrieveAllSpecialisations();
    ?>
    <style type="text/css">
    .111specfilter {display: none;}
    .timetableWrapper {display:none;}
    </style>

    <div class="filter">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Stream</h2>
        </div>
        <span class="filter-stream">
            <ul>
                <?php generateStreamFilters(); ?>
            </ul>
        </span>
    </div>

    <div class="messagebox" id="filterSelectMsg">
        <p style="text-align:center">Select a stream to view a timetable</p>
    </div>

    <div class="filter specfilter">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Specialisation</h2>
        </div>
        <span class="searchbar">
            <img src="IMG/search.png" class="inline-image searchIcon">
            <input type="text" id="SpecFilter" placeholder="Search for a topics" class="searchSpan">
        </span>
        <span class="filter-stream">
            <script type="text/javascript">
                FilterBySpecialisations();
            </script>
        </span>
    </div>
    <?php } ?>

    <div class="timetableWrapper">

        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Timetable</h2>
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
                if(!isset($oneStudent)){
                    $lq="SELECT facilitators.student_id, stu_name_first, stu_name_last, shift_id, shi_day, shi_duration, shi_time, str_shortname, str_name
                    FROM STIMulate.facilitators
                    JOIN STIMulate.shifts ON facilitators.student_id=shifts.student_id
                    JOIN STIMulate.streams ON shifts.shi_stream=streams.stream_id
                    WHERE facilitators.active = 1
                    ORDER BY shift_id asc;";
                    $sqlObject = new \php\SqlObject($lq);
                } else {
                    $lq = "SELECT facilitators.student_id, stu_name_first, stu_name_last, shift_id, shi_day, shi_duration, shi_time, str_shortname, str_name
                            FROM STIMulate.facilitators
                            JOIN STIMulate.shifts ON facilitators.student_id=shifts.student_id
                            JOIN STIMulate.streams ON shifts.shi_stream=streams.stream_id
                            WHERE facilitators.active = 1 AND shifts.student_id = :student_id
                            ORDER BY shift_id asc;";
                    $sqlObject = new \php\SqlObject($lq, array($_SESSION['user_id']));
                }
                $shiftInformation = $sqlObject->Execute();

                $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.absences WHERE DATE_ADD(NOW(), INTERVAL 7 DAY) >= absence_timestamp;");
                $absentInformation = $sqlObject->Execute();

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
                            $shiftTime = date("H:i", strtotime($shiftInformation[$i]["shi_time"]));

                            if($currentTime == $shiftTime) {
                                $rightTime = true;
                            } else if($currentTime+$shiftInformation[$i]['shi_duration'] <= $shiftTime+$shiftInformation[$i]['shi_duration'] &&
                                $currentTime >= $shiftTime){
                                $rightTime = true;
                            } else {
                                $rightTime = false;
                            }

                            if($rightTime) {
                                if(count($absentInformation) > 0) {
                                    $inputtedNameCards = 0;
                                    for($j = 0; $j < count($absentInformation); $j++){
                                        $absentDay = date("w", strtotime($absentInformation[$j]["absence_timestamp"]))-1;
                                        $absentTime = date("H:i", strtotime($absentInformation[$j]["absence_timestamp"]));

                                        if($shiftTime == $absentTime && $day == $absentDay &&
                                            $absentInformation[$j]["volunteer_id"] == $shiftInformation[$i]["student_id"]){
                                            NameCard($shiftInformation[$i], $day, true);
                                            $inputtedNameCards++;
                                            break;
                                        } else if($absentTime+$shiftInformation[$i]['shi_duration'] <= $shiftTime+$shiftInformation[$i]['shi_duration'] &&
                                            $currentTime >= $shiftTime) {
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
                        echo "<div class='".$shiftInformation["student_id"]." absent namecard f-".$shiftInformation["str_shortname"]." day".$day." time".$shiftInformation["shi_time"]." id".$shiftInformation["student_id"]."'>";
                    } else {
                        echo "<div class='" . $shiftInformation["student_id"] . " namecard f-".$shiftInformation["str_shortname"]." day".$day." time".$shiftInformation["shi_time"]." id".$shiftInformation["student_id"]."'>";
                    }
                    echo "<span class='".$shiftInformation['str_shortname']."'>".$shiftInformation["str_shortname"]."</span>";
                    echo $shiftInformation['stu_name_first']." ".substr($shiftInformation['stu_name_last'], 0,1);
                    echo "</div>";
                }

                function generateStreamFilters() {
                    $sqlObject = new \PHP\SqlObject("SELECT str_shortname, str_name FROM streams");
                    $data = $sqlObject->Execute();

                    foreach ($data as $stream) {
                        $shortcode = ucfirst(strtolower($stream['str_shortname']));
                        echo "<li id='filterStream".$shortcode."' class='stream-filters'>".$stream['str_name']."</li>";
                    }  
                }

                ?>
            </table>
        </div>
    </div>