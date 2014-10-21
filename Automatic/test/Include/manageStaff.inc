<?php
require_once 'PHP/SqlObject.php';
$sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.staff WHERE active =1 ORDER BY staff_name_first asc");
$facilitatorInfo = $sqlObject->Execute();

if(isset($_POST)){
    if(isset($_POST['id'], $_POST['type'])){
        if(isset($_POST['name'])) {
            $name = explode(' ', $_POST['name']);
            $first = $name[0];
            if (isset($name[1])) {
                $last = $name[1];
            } else {
                $last = " ";
            }
        }
        switch($_POST['type']){
            case 'new':
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.staff (staff_id, staff_name_first, staff_name_last, active)
                VALUES (:id, :name, :last, 1);", array($_POST['id'], $first, $last));
                $sqlObject->Execute();
                break;
            case 'manage':

                $sqlObject = new \PHP\SqlObject("UPDATE STIMulate.staff
                SET staff_id = :newId, staff_name_first = :firstname, staff_name_last = :lastname
                WHERE staff_id = :id;", array($_POST['newId'], $first, $last, $_POST['id']));
                $sqlObject->Execute();
                break;
            case 'Delete':
                $deleteQuery = "UPDATE STIMulate.staff
                SET active = 0
                WHERE staff_id IN (";

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
                <th class = "tableHead">Staff Number</th>
                <th class = "tableHead">Name</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for($i = 0; $i < count($facilitatorInfo); $i++){
                $checked = '';
                echo '<tr>';
                echo '<td class="check-Small"><input type = "checkbox" '.$checked.'></td>';

                echo '<td class = "data-item">'.$facilitatorInfo[$i]['staff_id'].'</td>';
                echo '<td class = "data-item">'.$facilitatorInfo[$i]['staff_name_first'].' '.$facilitatorInfo[$i]['staff_name_last'].'</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>