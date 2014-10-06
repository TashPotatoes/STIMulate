<!--
Author: Pearl Gariano
-->
<?php
    require_once '/php/SqlObject.php';

    //$query = new \PHP\SqlObject(INSERT INTO STIMulate.preferences (student_id, faculty, `day`, `9`, `10`, `11`, `12`, `1`, `2`, `3`, `4`) VALUES (array($_SESSION['user_id']), 'stream', 'MONDAY', '3', '2', '1', '1', '0', '0', '0', '0');
?>

<div class="push-right">
    <div class = "timetableWrapper">

        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Timetable Preferences</h2>
        </div>
        <div>
            <p>Please select your stream: 
                <select>
                    <option value="default">Stream Here...</option>
                    <option value="IT">IT</option>
                    <option value="science">Science</option>
                    <option value="maths">Maths</option>
                    <option value="duty-host">Duty Host</option>
                </select>
            </p>
            <p>To enter preferences, click on the cells until they change to the correct colour.</p>
            <p>Green is first preference, yellow is second peference and red is last preference. 
                Ensure any times you aren't available are white.</p>
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
                            $time="1";
                            break;
                        case 5:
                            $time="2";
                            break;
                        case 6:
                            $time="3";
                            break;
                        case 7:
                            $time="4";
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
                        echo "<td class='$time $day'>";
                        echo "</td>";
                    }
                    echo '</tr>';
                    $row++;
                }
                ?>
            </table>
        </div>
        <p>How many hours you would like to volunteer for your stream per week? 
            <select>
                <option value="default">Hours Here...</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </p>
        <input class="submit" type="submit" value="Submit">
    </div>
</div>
