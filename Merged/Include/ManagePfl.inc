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

    <div class = "filter">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Specialisation</h2>
        </div>
    <span class = "filter-stream">
        <ul>
            <li class = "admin-controls">New</li>
            <li class = "admin-controls">Manage</li>
            <li class = "admin-controls">Delete</li>
        </ul>
    </span>
    </div>

    <div class="contentWrapper">
       <table id = "InformationTable">
            <thead>
                <tr>
                    <th class = "check-Small"><input type="checkbox"></th>
                    <th class = "tableHead">Student Number</th>
                    <th class = "tableHead">Name</th>
                    <th class = "tableHead">Streams</th>
                </tr>
            </thead>
            <tbody>
            <?php
            for($i = 0; $i < count($facilitatorInfo); $i++){
                echo '<tr>';
                $checked = '';
                if($facilitatorInfo[$i]['active'] == 1) {
                    //$checked = 'CHECKED';
                }
                echo '<td class="check-Small"><input type = "checkbox" '.$checked.'></td>';

                echo '<td class = "data-item">'.$facilitatorInfo[$i]['student_id'].'</td>';
                echo '<td class = "data-item">'.$facilitatorInfo[$i]['stu_name_first'].' '.$facilitatorInfo[$i]['stu_name_last'].'</td>';
                echo '<td class = "data-item">';
                for($j = 0; $j < count($facilitatorstreams); $j++) {
                    $firstInputted = false;
                    for($k = 1; $k < (count($facilitatorstreams[$j])/2); $k++){
                        if($facilitatorstreams[$j][$k] == '1' && $facilitatorInfo[$i]['student_id'] == $facilitatorstreams[$j][0]){
                            if($firstInputted == false){
                                echo $streams[$k-1]['str_name'];
                                $firstInputted = true;
                            } else {
                                echo ', '.$streams[$k-1]['str_name'];
                            }
                        }
                    }

                }
                echo '</td>';
                $something = $facilitatorInfo[$i]['student_id'].'</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
       </table>
    </div>
</div>