<?php
require_once 'PHP/SqlObject.php';
$sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.absences ORDER BY volunteer_id asc");
$absceneInformation = $sqlObject->Execute();
$studentIds = array();

for($i = 0; $i < count($absceneInformation); $i++){
    array_push($studentIds, $absceneInformation[$i]['volunteer_id']);
}
$retrieveStudentInformation = "SELECT * FROM STIMulate.facilitators WHERE student_id in (";
for($i = 0; $i < count($studentIds); $i++){
    if($i+1 == count($studentIds)){
        $retrieveStudentInformation .= ":val".$i;
    } else {
        $retrieveStudentInformation .= ":val".$i.', ';
    }
}
$retrieveStudentInformation .= ") ORDER BY student_id asc;";

$sqlObject = new \PHP\SqlObject($retrieveStudentInformation, $studentIds);
$studentInformation = $sqlObject->Execute();

if(isset($_POST)) {
    if (isset($_POST['type'])) {
        switch($_POST['type']){
            case 'new':
                $time = date("Y-m-d H:i:s", strtotime($_POST['time']));
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.absences
                  (absence_timestamp, volunteer_id, reason) VALUES (:timestamp, :id, :reason)" , array($time, $_POST['id'], $_POST['reason']));
                $sqlObject->Execute();
                break;
            case 'Delete':
                $deleteQuery = "DELETE FROM STIMulate.absences WHERE absence_id IN (";
                for($i = 0; $i < count($_POST['absent_id']); $i++){
                    if($i+1 == count($_POST['absent_id'])){
                        $deleteQuery .= ":val".$i;
                    } else {
                        $deleteQuery .= ":val".$i.', ';
                    }
                }

                $deleteQuery .= ");";
                $sqlObject = new \PHP\SqlObject($deleteQuery, $_POST['absent_id']);
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
                <th class = "tableHead">Student ID <span class = "arrow-down"></span></th>
                <th class = "tableHead">Student Name<span class = "arrow-down"></span></th>
                <th class = "tableHead">Time Absent<span class = "arrow-down"></span></th>
                <th class = "tableHead">Reason<span class = "arrow-down"></span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
            for($i = 0; $i < count($absceneInformation); $i++){
                for($j=0;$j < count($studentInformation);$j++){
                    if($absceneInformation[$i]['volunteer_id'] == $studentInformation[$j]['student_id']){
                        echo '<tr>';
                        echo '<td class="check-Small"><input type = "checkbox" ></td>';
                        echo '<td class = "data-item">'.$absceneInformation[$i]['volunteer_id'].'</td>';
                        echo '<td class = "data-item">'.$studentInformation[$j]['stu_name_first'].' '.$studentInformation[$j]['stu_name_last'].'</td>';
                        echo '<td class = "data-item">'.$absceneInformation[$i]['absence_timestamp'].'</td>';
                        echo '<td class = "data-item">'.$absceneInformation[$i]['reason'].'</td>';
                        echo '<td class = "data-item" HIDDEN><input type = "hidden" value = "'.$absceneInformation[$i]['absence_id'].'"></td>';
                        echo '</tr>';
                    }
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>