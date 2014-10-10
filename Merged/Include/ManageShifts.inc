<?php
require_once 'PHP/SqlObject.php';
$sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.shifts ORDER BY student_id asc");
$shiftInformation = $sqlObject->Execute();
$sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.streams");
$streams = $sqlObject->Execute();
if(isset($_POST)) {
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case 'new':
                $dayIndex = date("w", strtotime($_POST['day']))-1;
                $streamID = '';
                switch ($_POST['stream']) {
                    case 'IT':
                        $streamID = 2;
                        break;
                    case 'Duty Host':
                        $streamID = 1;
                        break;
                    case 'Maths':
                        $streamID = 3;
                        break;
                    case 'Science':
                        $streamID = 4;
                        break;
                }

                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.shifts (student_id, shi_stream, shi_day, shi_time,
                                            shi_duration) VALUES (:id, :stream, :day, :time, :duration);", array($_POST['id'], $streamID, $dayIndex, $_POST['time'], $_POST['duration']));
                $sqlObject->Execute();
                break;
            case 'Delete':
                $deleteQuery = "DELETE FROM STIMulate.shifts WHERE shift_id IN (";
                for($i = 0; $i < count($_POST['shift_id']); $i++){
                    if($i+1 == count($_POST['shift_id'])){
                        $deleteQuery .= ":val".$i;
                    } else {
                        $deleteQuery .= ":val".$i.', ';
                    }
                }


                $deleteQuery .= ");";
                $sqlObject = new \PHP\SqlObject($deleteQuery, $_POST['shift_id']);
                $sqlObject->Execute();
                break;
            default:
                break;
        }
    }
}
?>
<div class = "push-right">

    <div class = "filter">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Specialisation</h2>
        </div>
    <span class = "filter-stream">
        <ul>
            <li class = "admin-controls">New</li>
            <li class = "admin-controls">Delete</li>
        </ul>
    </span>
    </div>

    <div class="contentWrapper">
        <table id = "InformationTable">
            <thead>
            <tr>
                <th class = "check-Small check-head"><input type="checkbox"></th>
                <th class = "tableHead">Student ID<span class = "arrow-down"></span></th>
                <th class = "tableHead">Stream<span class = "arrow-down"></span></th>
                <th class = "tableHead">Day<span class = "arrow-down"></span></th>
                <th class = "tableHead">Time<span class = "arrow-down"></span></th>
                <th class = "tableHead">Duration<span class = "arrow-down"></span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
            for($i = 0; $i < count($shiftInformation); $i++){
                $time = $shiftInformation[$i]['shi_time'];

                if(date("h:i", strtotime($shiftInformation[$i]['shi_time'])) < date("h:i", strtotime("9:00"))) {
                    $time += 12;
                }
                if($time >= 12) {
                    $shiftInformation[$i]['shi_time'] .= 'pm';
                } else {
                    $shiftInformation[$i]['shi_time'] .= 'am';
                }

                for ($j = 0; $j < count($streams); $j++){
                    if($streams[$j]['stream_id'] == $shiftInformation[$i]['shi_stream']){
                        $stream = $streams[$j]['str_name'];
                    }
                }
                echo '<tr>';
                echo '<td class="check-Small"><input type = "checkbox" ></td>';
                echo '<td class = "data-item">'.$shiftInformation[$i]['student_id'].'</td>';
                echo '<td class = "data-item">'.$stream.'</td>';
                echo '<td class = "data-item">'.$days[$shiftInformation[$i]['shi_day']].'</td>';
                echo '<td class = "data-item">'.$shiftInformation[$i]['shi_time'].'</td>';
                echo '<td class = "data-item">'.$shiftInformation[$i]['shi_duration'].' Hour(s)</td>';
                echo '<td class = "data-item" HIDDEN><input type = "hidden" value = "'.$shiftInformation[$i]['shift_id'].'"></td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>