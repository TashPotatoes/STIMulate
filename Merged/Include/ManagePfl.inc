<?php
    require_once 'PHP/SqlObject.php';
    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.facilitators ORDER BY stu_name_first asc");
    $facilitatorInfo = $sqlObject->Execute();

    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.streams");
    $streams = $sqlObject->Execute();

    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.facilitator_streams");
    $facilitatorstreams = $sqlObject->Execute();

    $showSearch = false;
    $placeholder = "Student Name, Student Number...";
    $headName = "Search";
?>
<div class = "push-right">
    <div class="contentWrapper">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Manage</h2>
        </div>

    </div>

    <?php include 'Include/TimetableFilter.inc';?>

    <div class="contentWrapper">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Student Records</h2>
        </div>

        <table id = "studentInformationTable">
            <tbody>
            <tr>
                <th>Student Number</th>
                <th>Student Name</th>
                <?php
                for($i = 0; $i < count($streams); $i++) {
                    echo '<th>'.$streams[$i]['str_name'].'</th>';
                }
                ?>
                <th>Active</th>
            </tr>
            <?php
            for($i = 0; $i < count($facilitatorInfo); $i++){
                if($facilitatorInfo[$i]['active'] == 1) {
                    $active = 'CHECKED';
                } else {
                    $active = '';
                }
                echo '<tr>';
                echo '<td>'.($i+1).'. '.$facilitatorInfo[$i]['student_id'].'</td>';
                echo '<td>'.$facilitatorInfo[$i]['stu_name_first'].' '.$facilitatorInfo[$i]['stu_name_last'].'</td>';
                for($j = 0; $j < count($streams); $j++) {
                    $checked = '';
                    for($k = 0; $k < count($facilitatorstreams); $k ++) {

                        if($facilitatorstreams[$k]['stream_id'] == $streams[$j]['stream_id'] && $facilitatorstreams[$k]['student_id'] == $facilitatorInfo[$i]['student_id']){
                            $checked = 'checked';
                            break;
                        }
                    }

                    echo '<td><input type = "checkbox" '.$checked.'></td>';
                }
                echo '<td><input type = "checkbox" '.$active.'></td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>