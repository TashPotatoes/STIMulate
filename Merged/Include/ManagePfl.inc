<?php
    require_once 'PHP/SqlObject.php';
    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.facilitators WHERE active = 1 ORDER BY stu_name_first asc");
    $facilitatorInfo = $sqlObject->Execute();

    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.streams");
    $streams = $sqlObject->Execute();

    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.facilitator_streams");
    $facilitatorstreams = $sqlObject->Execute();

    $showSearch = false;
    $placeholder = "Student Name, Student Number...";
    $headName = "Search";

if(isset($_POST)){
    if(isset($_POST['id'], $_POST['type'])){
        if(isset($_POST['name'], $_POST['stream'])) {
            $name = explode(' ', $_POST['name']);
            $first = $name[0];
            if (isset($name[1])) {
                $last = $name[1];
            } else {
                $last = "";
            }
        }
        switch($_POST['type']){
            case 'new':
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.facilitators (student_id, stu_name_first, stu_name_last, active)
                VALUES (:id, :name, :last, 1);", array($_POST['id'], $first, $last));
                $sqlObject->Execute();
                break;
            case 'manage':
                $sqlObject = new \PHP\SqlObject("UPDATE STIMulate.facilitators
                SET student_id = :id, stu_name_first = :firstname, stu_name_last = :lastname
                WHERE student_id = :id;", array($_POST['id'], $first, $last));
                $sqlObject->Execute();
                break;
            case 'Delete':
                $deleteQuery = "UPDATE STIMulate.facilitators
                SET active = 0
                WHERE student_id IN (";

                for($i = 0; $i < count($_POST['id']); $i++){
                    if($i+1 == count($_POST['id'])){
                        $deleteQuery .= ":val".$i;
                    } else {
                        $deleteQuery .= ":val".$i.', ';
                    }
                }
                $deleteQuery .= ");";

                $sqlObject = new \PHP\SqlObject($deleteQuery, $_POST['id']);
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
            <li class = "admin-controls">Manage</li>
            <li class = "admin-controls">Delete</li>
        </ul>
    </span>
    </div>

    <div class="contentWrapper">
       <table id = "InformationTable">
            <thead>
                <tr>
                    <th class = "check-Small check-head"><input type="checkbox"></th>
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