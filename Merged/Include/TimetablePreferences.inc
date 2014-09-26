<!--
Author: Pearl Gariano
-->
<?php
    require_once '/php/SqlObject.php';

    //$query = new \PHP\SqlObject(INSERT );
?>

<!-- <div class="timetableWrapper">
<form method="post" action="TimetablePreferences.php">
    <p>Please select your stream</p>
    <select>
      <option value="IT">IT</option>
      <option value="science">Science</option>
      <option value="maths">Maths</option>
      <option value="duty-host">Duty Host</option>
    </select>
    <p>To enter preferences click on the cells (next to the times) until they change colour.</p>
    <p>Green is first preference, yellow is second peference and red is last preference. Leave any times you aren't available white.</p>
        <table>
            <tr>
                <th colspan="2">Monday</th>
                <th colspan="2">Tuesday</th>
                <th colspan="2">Wednesday</th>
                <th colspan="2">Thursday</th>
                <th colspan="2">Friday</th>
            </tr>
             green is 3, yellow is 2, red is 1, white is null
            <tr>
                <td class="column-time">9-10</td>
                <td class="column-colour"></td>
                <td class="column-time">9-10</td>
                <td class="column-colour"></td>
                <td class="column-time">9-10</td>
                <td class="column-colour"></td>
                <td class="column-time">9-10</td>
                <td class="column-colour"></td>
                <td class="column-time">9-10</td>
                <td class="column-colour"></td>
            </tr>
            <tr>
                <td class="column-time">10-11</td>
                <td class="column-colour"></td>
                <td class="column-time">10-11</td>
                <td class="column-colour"></td>
                <td class="column-time">10-11</td>
                <td class="column-colour"></td>
                <td class="column-time">10-11</td>
                <td class="column-colour"></td>
                <td class="column-time">10-11</td>
                <td class="column-colour"></td>
            </tr>
            <tr>
                <td class="column-time">11-12</td>
                <td class="column-colour"></td>
                <td class="column-time">11-12</td>
                <td class="column-colour"></td>
                <td class="column-time">11-12</td>
                <td class="column-colour"></td>
                <td class="column-time">11-12</td>
                <td class="column-colour"></td>
                <td class="column-time">11-12</td>
                <td class="column-colour"></td>
            </tr>
            <tr>
                <td class="column-time">12-1</td>
                <td class="column-colour"></td>
                <td class="column-time">12-1</td>
                <td class="column-colour"></td>
                <td class="column-time">12-1</td>
                <td class="column-colour"></td>
                <td class="column-time">12-1</td>
                <td class="column-colour"></td>
                <td class="column-time">12-1</td>
                <td class="column-colour"></td>
            </tr>
            <tr>
                <td class="column-time">1-2</td>
                <td class="column-colour"></td>
                <td class="column-time">1-2</td>
                <td class="column-colour"></td>
                <td class="column-time">1-2</td>
                <td class="column-colour"></td>
                <td class="column-time">1-2</td>
                <td class="column-colour"></td>
                <td class="column-time">1-2</td>
                <td class="column-colour"></td>
            </tr>
            <tr>
                <td class="column-time">2-3</td>
                <td class="column-colour"></td>
                <td class="column-time">2-3</td>
                <td class="column-colour"></td>
                <td class="column-time">2-3</td>
                <td class="column-colour"></td>
                <td class="column-time">2-3</td>
                <td class="column-colour"></td>
                <td class="column-time">2-3</td>
                <td class="column-colour"></td>
            </tr>
            <tr>
                <td class="column-time">3-4</td>
                <td class="column-colour"></td>
                <td class="column-time">3-4</td>
                <td class="column-colour"></td>
                <td class="column-time">3-4</td>
                <td class="column-colour"></td>
                <td class="column-time">3-4</td>
                <td class="column-colour"></td>
                <td class="column-time">3-4</td>
                <td class="column-colour"></td>
            </tr>
            <tr>
                <td class="column-time">4-5</td>
                <td class="column-colour"></td>
                <td class="column-time">4-5</td>
                <td class="column-colour"></td>
                <td class="column-time">4-5</td>
                <td class="column-colour"></td>
                <td class="column-time">4-5</td>
                <td class="column-colour"></td>
                <td class="column-time">4-5</td>
                <td class="column-colour"></td>
            </tr>   
        </table>
    
    <input type="submit" value="Submit">
</form>

</div> -->

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
                    //InsertActivity($shiftInformation, $absentInformation, $j, $currentTime);
                    echo "</td>";
                }
                echo '</tr>';
                $row++;
            } 
            ?>
         </table>
    </div>
</div>
