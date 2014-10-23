<?php
//Requiring SQL Object
require_once 'PHP/SqlObject.php';

?>

<?php
	// for the user to select the stream
	//$stream = "IT";
	if(isset($_POST['stream'])) {
		$stream =  $_POST['stream'];
		echo "<p>Current Stream: " . $stream . "</p>";
		generateCPLEX($stream);?>
		
		<input type="submit" value= "Generate Table" onclick="run()" />
		<pre id="log"/>
	<?php 
		//resultsToDatabase();
	} else {
?>	
		<div>
			<form name="streamSelect" action="test.php" method="post">
			<input type="radio" name="stream" id="stream-it"value="it"><label for="stream-it">IT</label><br/>
			<input type="radio" name="stream" id="stream-ma"value="ma"><label for="stream-ma">Maths</label><br/>
			<input type="radio" name="stream" id="stream-sc"value="sc"><label for="stream-sc">Science</label><br/>
			<input type="radio" name="stream" id="stream-dh"value="dh"><label for="stream-dh">Duty Host</label><br/>
			<input type="submit" value="Select Stream">
			</form>
		</div>
<?php	
	} 	
?>
<script>
        var start;
		var logNode = document.getElementById("log");
        var log = glp_print_func = function(value){
            var now = new Date();
	        var d = (now.getTime() - start.getTime()) / 1000;
	        logNode.appendChild(document.createTextNode(value + "\n"));
            if (d > 60) throw new Error("timeout");
	        console.log(value);
        };
</script>



/*
 * Based on the page get, retrieve the right information and return the data
 * @return array An associative array of database data
 */
function babla(){}

/*
 * Determines the appropriate html to put into the button control box
 * Echos html into browser
 */
function determineControls(){
    // Define all button options, and unset them as needed using the switch
    $buttonHtml = array('newButton' => '<li class = "admin-controls">New</li>','manageButton' => '<li class = "admin-controls">Manage</li>',
        'deleteButton' => '<li class = "admin-controls">Delete</li>', 'addCSVButton' => '<li class = "admin-controls">Add by CSV</li>');

    // Depending on the action return the appropraite information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case null:
            // Default action. Display nothing
            $buttonHtml = array();
            break;
        case 'manageAbsent':
            unset($buttonHtml['addCSVButton']);
            break;
        default:
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

function insertRows($sqlResult){
    // Depending on the action return the appropraite information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case 'manageVolunteer':
            insertVolunteer($sqlResult);
            break;
        case 'manageStaff':
            insertStaff($sqlResult);
            break;
        case 'manageShift':
            insertShift($sqlResult);
            break;
        case 'manageAbsent':
            insertAbsent($sqlResult);
            break;
        // Default action. End
        default:
            return false;
    }
}

function insertVolunteer($sqlResult){
    for($i = 0; $i < count($sqlResult['volunteerInformation']); $i++){
        echo '<tr>';
        $checked = '';
        if($sqlResult['volunteerInformation'][$i]['active'] == 1) {
            //$checked = 'CHECKED';
        }
        echo '<td class="check-Small"><input type = "checkbox" '.$checked.'></td>';

        echo '<td class = "data-item">'.$sqlResult['volunteerInformation'][$i]['student_id'].'</td>';
        echo '<td class = "data-item">'.$sqlResult['volunteerInformation'][$i]['stu_name_first'].' '.$sqlResult['volunteerInformation'][$i]['stu_name_last'].'</td>';
        echo '<td class = "data-item">';
        for($j = 0; $j < count($sqlResult['volunteerStreamInformation']); $j++) {
            $firstInputted = false;
            for($k = 1; $k < (count($sqlResult['volunteerStreamInformation'][$j])/2); $k++){
                if($sqlResult['volunteerStreamInformation'][$j][$k] == '1' && $sqlResult['volunteerInformation'][$i]['student_id'] == $sqlResult['volunteerStreamInformation'][$j][0]){
                    if($firstInputted == false){
                        echo $sqlResult['streamInformation'][$k-1]['str_name'];
                        $firstInputted = true;
                    } else {
                        echo ', '.$sqlResult['streamInformation'][$k-1]['str_name'];
                    }
                }
            }

        }
        echo '</td>';
        echo '</tr>';
    }
}

function insertStaff($sqlResult){
    for($i = 0; $i < count($sqlResult['staffInformation']); $i++){
        $checked = '';
        echo '<tr>';
        echo '<td class="check-Small"><input type = "checkbox" '.$checked.'></td>';

        echo '<td class = "data-item">'.$sqlResult['staffInformation'][$i]['staff_id'].'</td>';
        echo '<td class = "data-item">'.$sqlResult['staffInformation'][$i]['staff_name_first'].' '.$sqlResult['staffInformation'][$i]['staff_name_last'].'</td>';
        echo '</tr>';
    }
}

function insertShift($sqlResult) {
    $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
    for ($i = 0; $i < count($sqlResult['shiftInformation']); $i++) {
        $time = $sqlResult['shiftInformation'][$i]['shi_time'];

        if (date("h:i", strtotime($sqlResult['shiftInformation'][$i]['shi_time'])) < date("h:i", strtotime("9:00"))) {
            $time += 12;
        }
        if ($time >= 12) {
            $sqlResult['shiftInformation'][$i]['shi_time'] .= 'pm';
        } else {
            $sqlResult['shiftInformation'][$i]['shi_time'] .= 'am';
        }

        for ($j = 0; $j < count($sqlResult['streamInformation']); $j++) {
            if ($sqlResult['streamInformation'][$j]['stream_id'] == $sqlResult['shiftInformation'][$i]['shi_stream']) {
                $stream = $sqlResult['streamInformation'][$j]['str_name'];
            }
        }
        echo '<tr>';
        echo '<td class="check-Small"><input type = "checkbox" ></td>';
        echo '<td class = "data-item">' . $sqlResult['shiftInformation'][$i]['student_id'] . '</td>';
        echo '<td class = "data-item">' . $stream . '</td>';
        echo '<td class = "data-item">' . $days[$sqlResult['shiftInformation'][$i]['shi_day']] . '</td>';
        echo '<td class = "data-item">' . $sqlResult['shiftInformation'][$i]['shi_time'] . '</td>';
        echo '<td class = "data-item">' . $sqlResult['shiftInformation'][$i]['shi_duration'] . ' Hour(s)</td>';
        echo '<td class = "data-item" HIDDEN><input type = "hidden" value = "' . $sqlResult['shiftInformation'][$i]['shift_id'] . '"></td>';
        echo '</tr>';
    }
}

function insertAbsent($sqlResult) {
    for($i = 0; $i < count($sqlResult['absentInformation']); $i++){
        for($j=0;$j < count($sqlResult['absentStudentInformation']);$j++){
            if($sqlResult['absentInformation'][$i]['volunteer_id'] == $sqlResult['absentStudentInformation'][$j]['student_id']){
                echo '<tr>';
                echo '<td class="check-Small"><input type = "checkbox" ></td>';
                echo '<td class = "data-item">'.$sqlResult['absentInformation'][$i]['volunteer_id'].'</td>';
                echo '<td class = "data-item">'.$sqlResult['absentStudentInformation'][$j]['stu_name_first'].' '.$sqlResult['absentStudentInformation'][$j]['stu_name_last'].'</td>';
                echo '<td class = "data-item">'.$sqlResult['absentInformation'][$i]['absence_timestamp'].'</td>';
                echo '<td class = "data-item">'.$sqlResult['absentInformation'][$i]['absent_end_timestamp'].'</td>';
                echo '<td class = "data-item">'.$sqlResult['absentInformation'][$i]['reason'].'</td>';
                echo '<td class = "data-item" HIDDEN><input type = "hidden" value = "'.$sqlResult['absentInformation'][$i]['absence_id'].'"></td>';
                echo '</tr>';
            }
        }
    }
}

function handlePost(){
    switch($_GET['action']){
        case 'manageVolunteer':
            manageVolunteer();
            break;
        case 'manageStaff':
            manageStaff();
            break;
        case 'manageShift':
            manageShift();
            break;
        case 'manageAbsent':
            manageAbsent();
            break;
        default:
            break;
    }
}

function manageAbsent()
{
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case 'new':
                $time = date("Y-m-d H:i:s", strtotime($_POST['time']));
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.absences
                  (absence_timestamp, volunteer_id, reason) VALUES (:timestamp, :id, :reason)", array($time, $_POST['id'], $_POST['reason']));
                $sqlObject->Execute();
                break;
            case 'delete':
                $deleteQuery = "DELETE FROM STIMulate.absences WHERE absence_id IN (";
                for ($i = 0; $i < count($_POST['absent_id']); $i++) {
                    if ($i + 1 == count($_POST['absent_id'])) {
                        $deleteQuery .= ":val" . $i;
                    } else {
                        $deleteQuery .= ":val" . $i . ', ';
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

function manageShift()
{
    if (isset($_POST['type'])) {
        var_dump($_POST);
        switch ($_POST['type']) {
            case 'new':
                $dayIndex = date("w", strtotime($_POST['day'])) - 1;
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
            case 'delete':
                $deleteQuery = "DELETE FROM STIMulate.shifts WHERE shift_id IN (";
                for ($i = 0; $i < count($_POST['shift_id']); $i++) {
                    if ($i + 1 == count($_POST['shift_id'])) {
                        $deleteQuery .= ":val" . $i;
                    } else {
                        $deleteQuery .= ":val" . $i . ', ';
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

function manageStaff()
{
    if (isset($_POST['id'], $_POST['type'])) {
        if (isset($_POST['name'])) {
            $name = explode(' ', $_POST['name']);
            $first = $name[0];
            if (isset($name[1])) {
                $last = $name[1];
            } else {
                $last = " ";
            }
        }
        switch ($_POST['type']) {
            case 'new':
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.staff (staff_id, staff_name_first,
              staff_name_last, staff_password, active)
                VALUES (:id, :name, :last, MD5(:pass), 1);", array($_POST['id'], $first, $last, $_POST['password']));
                $sqlObject->Execute();
                break;
            case 'manage':

                $sqlObject = new \PHP\SqlObject("UPDATE STIMulate.staff
                SET staff_id = :newId, staff_name_first = :firstname, staff_name_last = :lastname
                WHERE staff_id = :id;", array($_POST['newId'], $first, $last, $_POST['id']));
                $sqlObject->Execute();
                break;
            case 'delete':
                $deleteQuery = "UPDATE STIMulate.staff
                SET active = 0
                WHERE staff_id IN (";

                for ($i = 0; $i < count($_POST['id']); $i++) {
                    if ($i + 1 == count($_POST['id'])) {
                        $deleteQuery .= ":val" . $i;
                    } else {
                        $deleteQuery .= ":val" . $i . ', ';
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

function manageVolunteer()
{
    if (isset($_POST['id'], $_POST['type'])) {
        if (isset($_POST['name'], $_POST['stream'])) {
            $name = explode(' ', $_POST['name']);
            $first = $name[0];
            if (isset($name[1])) {
                $last = $name[1];
            } else {
                $last = "";
            }
        }
        switch ($_POST['type']) {
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
            case 'delete':
                $deleteQuery = "UPDATE STIMulate.facilitators
                                        SET active = 0
                                        WHERE student_id IN (";

                for ($i = 0; $i < count($_POST['id']); $i++) {
                    if ($i + 1 == count($_POST['id'])) {
                        $deleteQuery .= ":val" . $i;
                    } else {
                        $deleteQuery .= ":val" . $i . ', ';
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
