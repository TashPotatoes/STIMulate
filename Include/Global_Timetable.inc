<?php require_once '/php/DatabaseAPI.php'; ?>
    <?php
        if("index.php" == basename($_SERVER['PHP_SELF'])) {

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

                // If retrieving all or just one student's data
                if(!isset($oneStudent)){
                    $lq="SELECT facilitators.student_id, stu_name_first, stu_name_last, shift_id, shi_day, shi_duration, shi_time, str_shortname, str_name, is_IT, is_SC, is_MA, is_DH
                    FROM STIMulate.facilitators
                    JOIN STIMulate.shifts ON facilitators.student_id=shifts.student_id
                    JOIN STIMulate.streams ON shifts.shi_stream=streams.stream_id
                    LEFT JOIN STIMulate.facilitator_streams ON facilitators.student_id=facilitator_streams.user_id
                    WHERE facilitators.active = 1
                    ORDER BY shift_id asc;";
                    $sqlObject = new \php\SqlObject($lq);
                } else {
                    $lq = "SELECT facilitators.student_id, stu_name_first, stu_name_last, shift_id, shi_day, shi_duration, shi_time, str_shortname, str_name, is_IT, is_SC, is_MA, is_DH
                            FROM STIMulate.facilitators
                            JOIN STIMulate.shifts ON facilitators.student_id=shifts.student_id
                            JOIN STIMulate.streams ON shifts.shi_stream=streams.stream_id
                            LEFT JOIN STIMulate.facilitator_streams ON facilitators.student_id=facilitator_streams.user_id
                            WHERE facilitators.active = 1 AND shifts.student_id = :student_id
                            ORDER BY shift_id asc;";
                    $sqlObject = new \php\SqlObject($lq, array($_SESSION['user_id']));
                }
                $shiftInformation = $sqlObject->Execute();

                // Retreive absent informaiton
                $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.absences WHERE DATE_ADD(NOW(), INTERVAL 7 DAY) >= absence_timestamp
                                                  AND absence_timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY) OR absent_end_timestamp > NOW();");
                $absentInformation = $sqlObject->Execute();

                $startOfWeek = date("d-m-Y", strtotime('this week', time()));

                $DAYS_IN_WEEK = 5;
                $START_HOUR = '08:00:00'; // Must be before 12 noon, whole hour only (0-12), set to hour before earliest shift. // CHANGE THIS TO BE A QUERY
                $SECTIONS_IN_DAY = 8;

                $row = 1;

                // For number of hour periods in a day
                for($i = 0; $i < $SECTIONS_IN_DAY; $i++){

                    // Get the current time
                    $currentTime = date("H:i", strtotime('+'.($row*60).'minutes', strtotime($START_HOUR)));
                    echo '<tr>';
                    echo '<td class="column-time">'.$currentTime.'</td>';
                    // For the number of days in a week each in the time's data
                    for($j = 0; $j < $DAYS_IN_WEEK; $j++){
                        echo "<td>";
                        // Insert activity if needed
                        InsertActivity($shiftInformation, $absentInformation, $j, $currentTime);
                        echo "</td>";
                    }
                    echo '</tr>';
                    $row++;
                }

                /*
                 * Determines if an activity exists, and if so whether or not is absent.
                 * PRE: $shiftInformation - array Array of every facilitators information
                 * PRE: $absentInformation - array Array of every facilitators absent information
                 * PRE: $day - int current day index (0 -> end day int)
                 * PRE: $currentTime - string current time
                 * POST: Shift inserted or not absent or not.
                 */
                function InsertActivity($shiftInformation, $absentInformation, $day, $currentTime){

                    // For each of the shifts, if correct day, if current time shift time,
                    // if there's absent information, foreach of the absent records,
                    // if the volunteer id matches the absent volunteer id, insert as absent.
                    for($i = 0; $i < count($shiftInformation); $i++){

                        // Table is up to shift day
                        if($shiftInformation[$i]['shi_day'] == $day) {
                            // Turn shift time into date Object
                            $shiftTime = date("H:i", strtotime($shiftInformation[$i]["shi_time"]));
                            // Subtract one from duration for on the hour shift
                            $shiftDuration = $shiftInformation[$i]['shi_duration']-1;

                            // If the current time is the shift time continue else don't
                            if($currentTime == $shiftTime) {
                                $rightTime = true;
                            } else if(($currentTime+$shiftDuration) - ($shiftTime+$shiftDuration)
                                <= $shiftDuration &&
                                $currentTime >= $shiftTime){ //Handles shift durations if table time is in the range 9:00 -> 9:00 + 2 hours right time still
                                $rightTime = true;
                            } else {
                                $rightTime = false;
                            }

                            if($rightTime) {

                                // If absent information exists determine it's correct time else insert
                                // shift normally
                                if(count($absentInformation) > 0) {
                                    $inputtedNameCards = 0;

                                    // for each absent data, insert relevant information else insert non absent shift
                                    for($j = 0; $j < count($absentInformation); $j++) {
                                        $now = date("Y-m-d H:i:s", strtotime("Now +".($day+1)." days"));

                                        // If absent end time is set, ids match insert absent namecard
                                        if (isset($absentInformation[$j]['absent_end_timestamp']) && $absentInformation[$j]["volunteer_id"] == $shiftInformation[$i]["student_id"]) {
                                            $absentEnd = date("Y-m-d H:i:s", strtotime($absentInformation[$j]['absent_end_timestamp']));

                                            // If the end time is greater then now input
                                            if($absentEnd>$now){
                                                NameCard($shiftInformation[$i], $day, true, $now);
                                                $inputtedNameCards++;
                                                break;
                                            }
                                        } else {
                                            //
                                            $absentDay = date("w", strtotime($absentInformation[$j]["absence_timestamp"])) - 1;
                                            $absentTime = date("H:i", strtotime($absentInformation[$j]["absence_timestamp"]));

                                            // if ids match, days match, and times match input
                                            if ($shiftTime == $absentTime && $day == $absentDay &&
                                                $absentInformation[$j]["volunteer_id"] == $shiftInformation[$i]["student_id"]) {
                                                NameCard($shiftInformation[$i], $day, true, $now);
                                                $inputtedNameCards++;
                                                break;
                                            // If absent + duration is still less shift duration, ids match, times match, and times match
                                            // Insert
                                            } else if ($absentTime + $shiftDuration <= $shiftTime + $shiftDuration &&
                                                $currentTime >= $shiftTime && $day == $absentDay &&
                                                $absentInformation[$j]["volunteer_id"] == $shiftInformation[$i]["student_id"]) {
                                                NameCard($shiftInformation[$i], $day, true, $now);
                                                $inputtedNameCards++;
                                                break;
                                            }
                                        }
                                    }
                                    // Insert non absent card
                                    if($inputtedNameCards == 0) {
                                        NameCard($shiftInformation[$i], $day, false);
                                    }
                                // Insert non absent card
                                } else {
                                    NameCard($shiftInformation[$i], $day, false);
                                }
                            }
                        }
                    }
                }

                /*
                 * Inserts a name card in it's current called location
                 * PRE: $shiftInformation - array Array of every facilitators information
                 * PRE: $day - int current day index (0 -> end day int)
                 * PRE: $disabled- bool whether is absent or not
                 * PRE: $absentTimestamp - string if absent the current absent time
                 * POST: Shift inserted or not absent or not.
                 */
                function NameCard($shiftInformation, $day, $disabled, $absentTimestamp = '')
                {
                    // If absent float absent tag
                    if($disabled){
                        $date = Date('d/m/Y', strtotime($absentTimestamp));
                        $absentMsg = "Away on the " . $date;
                        echo "<div class='".$shiftInformation["student_id"]." absent namecard f-".$shiftInformation["str_shortname"]." day".$day." time".$shiftInformation["shi_time"]." id".$shiftInformation["student_id"]."' title='". $absentMsg ."'>";
                    } else {
                        echo "<div class='" . $shiftInformation["student_id"] . " namecard f-".$shiftInformation["str_shortname"]." day".$day." time".$shiftInformation["shi_time"]." id".$shiftInformation["student_id"]."'>";
                    }

                    $cardInpuuted = false;

                    // Insert stream flag as neccessary
                    if ($shiftInformation['is_IT']) {
                        echo "<span class='IT' alt='Info. Tech.'>IT</span>";
                        $cardInpuuted = true;
                    }
                    if ($shiftInformation['is_SC']) {
                        echo "<span class='SC' alt='Science'>SC</span>";
                        $cardInpuuted = true;
                    }
                    if ($shiftInformation['is_MA']) {
                        echo "<span class='MA' alt='Maths'>MA</span>";
                        $cardInpuuted = true;
                    }

                    // If a card hasn't been inserted insert just take shift tag
                    if(!$cardInpuuted) {
                        echo "<span class=\"".$shiftInformation['str_shortname']."\" alt=''>".$shiftInformation['str_shortname']."</span>";
                    }

                    // Echo in name
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