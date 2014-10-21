<?php
require_once 'PHP/SqlObject.php';

if (isset($_FILES['file'])) {
    $file = fopen($_FILES["file"]["tmp_name"], 'r');
    $CSVData = array();
    $numberColumns = 0;
    while (($line = fgetcsv($file)) !== FALSE) {
        //$line is an array of the csv elements
        array_push($CSVData, $line);
        $numberColumns = count($line);
    }
    fclose($file);
    unset($CSVData[0]);
}

if(isset($_POST)){
    if(count($_POST) > 0) {
        handlePost();
    }
}

// If the page is access with an appropraite get variable
if(isset($_GET['action'])) { ?>
<script>
    $(document).ready(function(){
        LoadUserInteractions(<?php echo JSON_ENCODE($_GET['action']); ?>);
    });
</script>
<div class = "push-right">
<div class = "filter">
    <div class = "headElement">
        <img src="IMG/calander.png" alt="Calander" class = "inline-image">
        <h2 class = "inline-text">Specialisation</h2>
    </div>
    <span class = "filter-stream">
        <ul>
            <?php
            determineControls();
            ?>
        </ul>
    </span>
</div>

<div class="contentWrapper">
    <table id = "InformationTable">
        <thead>
        <tr>
            <?php
            determineTableHead();
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($CSVData, $numberColumns)) {
            determineBody($CSVData, $numberColumns);
        }
        ?>
        </tbody>
    </table>
</div>
<?php }

/*
 * Determines the appropriate html to put into the button control box
 * Echos html into browser
 */
function determineControls(){
    // Define all button options, and unset them as needed using the switch
    $buttonHtml = array('addData' => '<li class = "admin-controls">Add to All</li>', 'addCSV' => '<li class = "admin-controls">Add CSV</li>');

    // Depending on the action return the appropraite information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case null:
            // Default action. Display nothing
            $buttonHtml = array();
            break;
        default:
            if(isset($_FILES)){
                if(count($_FILES) > 0){
                    unset($buttonHtml['addCSV']);
                } else {
                    unset($buttonHtml['addData']);
                }
            } else {
                unset($buttonHtml['addData']);
            }
            break;
    }

    // Echo html in as needed
    foreach($buttonHtml as $button){
        echo $button;
    }
}

function determineTableHead(){
    // Define all table head options, later the index chooses the correct one.
    $tableHeadHtml = array('manageVolunteer' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                    <th class = "tableHead">Student Number<span class = "arrow-down"></span></th>
                    <th class = "tableHead">Name<span class = "arrow-down"></span></th>
                    <th class = "tableHead">Streams<span class = "arrow-down"></span></th>',
        'manageStaff' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                    <th class = "tableHead">Staff Number<span class = "arrow-down"></span></th>
                    <th class = "tableHead">Name<span class = "arrow-down"></span></th>',
        'manageShift' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                <th class = "tableHead">Student ID<span class = "arrow-down"></span></th>
                <th class = "tableHead">Stream<span class = "arrow-down"></span></th>
                <th class = "tableHead">Day<span class = "arrow-down"></span></th>
                <th class = "tableHead">Time<span class = "arrow-down"></span></th>
                <th class = "tableHead">Duration<span class = "arrow-down"></span></th>',
        'manageAbsent' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                <th class = "tableHead">Student ID <span class = "arrow-down"></span></th>
                <th class = "tableHead">Student Name<span class = "arrow-down"></span></th>
                <th class = "tableHead">Time Absent<span class = "arrow-down"></span></th>
                <th class = "tableHead">End Absent Time<span class = "arrow-down"></span></th>
                <th class = "tableHead">Reason<span class = "arrow-down"></span></th>'
    );
    $index = '';

    // Echo html in as needed
    echo $tableHeadHtml[$_GET['action']];
    return true;
}

function determineBody($CSVData, $numberColumns) {
    if (isset($CSVData)) {
        for ($i = 1; $i < count($CSVData) + 1; $i++) {
            echo '<tr>';
            echo '<td class="check-Small"><input type = "checkbox" ></td>';
            for($j = 0; $j < $numberColumns; $j++){
                echo '<td class = "data-item">' . $CSVData[$i][$j] . '</td>';
            }
            echo '</tr>';
        }
    }
}

function handlePost(){
    $length = $_POST['numberVariables'];

    $sql = '';
    $array = array();
    switch($_GET['action']){
        case 'manageVolunteer':
            $sql = 'INSERT INTO STIMulate.facilitators (student_id, stu_name_first, stu_name_last, active) VALUES ';
            break;
        case 'manageStaff':
            $sql = 'INSERT INTO STIMulate.staff (staff_id, staff_name_first, staff_name_last, active) VALUES ';
            break;
        case 'manageShift':
            $sql = 'INSERT INTO STIMulate.shifts (student_id, shi_stream, shi_day, shi_time, shi_duration) VALUES ';
            break;
        default:
            break;
    }
    $sql = manageSqlGeneration($sql, $length, $array);

    if($_GET['action']=='manageShift'){
        $array[4] = explode(' ', $array[4])[0];

        if(substr($array[3], -count($array[3])-1) == 'pm') {
            $timeExploded = explode(':', substr($array[3], 0, strlen($array[3])-2));
            $timeExploded[0] += 12;
            $time = $timeExploded[0].':'.$timeExploded[1];
        } else {
            $time = substr($array[3], 0, -2);
        }
        $array[3] = $time;
    }

    $sqlObject = new \PHP\SqlObject($sql, $array);
    $sqlObject->Execute();
}

function manageSqlGeneration($sql, $length, &$array){
    for($i =0; $i < $length; $i++){
        for($j = 0; $j < count($_POST['id'.$i]); $j++){
            if($j == 0){
                $sql .= '(';
            }
            $noComma = false;
            $data = $_POST['id'.$i][$j];
            if($j == 1 && $_GET['action'] != 'manageShift') {
                $sql .= ':'.$i.$j;
                $data = explode(' ', $_POST['id'.$i][$j]);
                array_push($array, $data[0]);
                $sql .= ', :'.$i.$j.'num2';
                array_push($array, $data[1]);
            } else if($j == count($_POST['id'.$i])-1) { // TEMPORARY FIX TO STREAM MIX
                if($_GET['action'] == 'manageShift'){
                    $sql .= ':'.$i.$j;
                    array_push($array, $data);
                }
                $noComma = true;
            } else {
                array_push($array, $data);
                $sql .= ':'.$i.$j;
            }

            if($noComma){
                $noComma = false;
            } else {
                $sql .= ', ';
            }
            if($j == count($_POST['id'.$i])-1) {
                if ($_GET['action'] != 'manageShift') {
                    $sql .= '1)';
                } else {
                    $sql .= ')';
                }
            }
        }
        if($i != $length-1){
            $sql .= ', ';
        } else {
            $sql .= ';';
        }
    }
    return $sql;
}
?>
