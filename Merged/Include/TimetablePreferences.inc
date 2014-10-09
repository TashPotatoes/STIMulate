<!--
Author: Pearl Gariano
-->

<div class="push-right pageWrapper">

    <div class="contentWrapper">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Timetable Preferences</h2>
        </div>
        <div>
            <p>Please select your stream:<br/>
                <input type="radio" name="stream" id="stream-it" value="IT"><label for="stream-it">IT</label><br/>
                <input type="radio" name="stream" id="stream-ma" value="MATH"><label for="stream-ma">Maths</label><br/>
                <input type="radio" name="stream" id="stream-sc" value="SCIENCE"><label for="stream-sc">Science</label><br/>
                <input type="radio" name="stream" id="stream-dh" value="DUTYHOST"><label for="stream-dh">Duty Host</label><br/>
            </p>
            <p>To enter preferences, click on the cells until they change to the correct colour.</p>

   
    </div>
    <div class = "timetableWrapper">

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
                $startOfWeek = date("d-m-Y", strtotime('this week', time()));

                $DAYS_IN_WEEK = 5;
                $START_HOUR = '08:00:00'; // Must be before 12 noon, whole hour only (0-12), set to hour before earliest shift. // CHANGE THIS TO BE A QUERY
                $SECTIONS_IN_DAY = 8;

                $row = 1;

                for($i = 0; $i < $SECTIONS_IN_DAY; $i++){
                    $currentTime = date("h:i", strtotime('+'.($row*60).'minutes', strtotime($START_HOUR)));
                    echo '<tr>';
                    echo '<td class="column-time">'.$currentTime.'</td>';

                    //stuff here for now... 
                    switch ($i) {
                        case 0:
                            $time="9";
                            break;
                        case 1:
                            $time="10";
                            break;
                        case 2:
                            $time="11";
                            break;
                        case 3:
                            $time="12";
                            break;
                        case 4:
                            $time="13";
                            break;
                        case 5:
                            $time="14";
                            break;
                        case 6:
                            $time="15";
                            break;
                        case 7:
                            $time="16";
                            break;
                        default:
                            $time="ERROR";
                            break;
                    }

                    for($j = 0; $j < $DAYS_IN_WEEK; $j++){
                        switch ($j) {
                            case 0:
                                $day="MONDAY";
                                break;
                            case 1:
                                $day="TUESDAY";
                                break;
                            case 2:
                                $day="WEDNESDAY";
                                break;
                            case 3:
                                $day="THURSDAY";
                                break;
                            case 4:
                                $day="FRIDAY";
                                break;
                            default:
                                $day="ERROR";
                                break;
                        }
                        echo "<td class='$time $day WHITE'>";
                        echo "</td>";
                    }
                    echo '</tr>';
                    $row++;
                }
                ?>
            </table>
        </div>
        <p>How many hours would you like to volunteer for your selected stream?<br/>
            <input type="radio" name="max-hour" id="max-hour-1" value="1"><label for="max-hour-1">1</label><br/>
            <input type="radio" name="max-hour" id="max-hour-2" value="2"><label for="max-hour-2">2</label><br/>
            <input type="radio" name="max-hour" id="max-hour-3" value="3"><label for="max-hour-3">3</label><br/>
            <input type="radio" name="max-hour" id="max-hour-4" value="4"><label for="max-hour-4">4</label><br/>
        </p>
        <input id="submit" type="submit" value="Submit">
    </div>
</div>
