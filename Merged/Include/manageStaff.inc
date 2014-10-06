<?php
require_once 'PHP/SqlObject.php';
$sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.staff ORDER BY staff_name_first asc");
$facilitatorInfo = $sqlObject->Execute();

$showSearch = false;
$placeholder = "Name, Number...";
$headName = "Search";
?>
<div class = "push-right">
    <div class="contentWrapper">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Manage</h2>
        </div>
        <div class = "manageWrapper">
            <ul>
                <li><input type="button" value="Add new Staff"></li>
            </ul>
        </div>
    </div>

    <?php include 'Include/TimetableFilter.inc';?>

    <div class="contentWrapper">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Staff Records</h2>
        </div>

        <table id = "studentInformationTable">
            <tbody>
            <tr>
                <th>Staff Number</th>
                <th>Staff Name</th>
            </tr>
            <?php
            for($i = 0; $i < count($facilitatorInfo); $i++){
                echo '<tr>';
                echo '<td>'.($i+1).'. '.$facilitatorInfo[$i]['staff_id'].'</td>';
                echo '<td>'.$facilitatorInfo[$i]['staff_name_first'].' '.$facilitatorInfo[$i]['staff_name_last'].'</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>