<?php
require_once 'PHP/SqlObject.php';


if (isset($_FILES['file'])) {
    $file = fopen($_FILES["file"]["tmp_name"], 'r');
    $CSVData = array();

    while (($line = fgetcsv($file)) !== FALSE) {
        //$line is an array of the csv elements
        array_push($CSVData, $line);
    }
    fclose($file);
    unset($CSVData[0]);
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
        insertRows($sqlResult);
        ?>
        </tbody>
    </table>
</div>
<?php }

/*
 * Based on the page get, retrieve the right information and return the data
 * @return array An associative array of database data
 */
function RetrieveData(){
    // Defining the SQL queries to pull data from the database
    $sqlQueries = ['absentSql' => 'SELECT * FROM STIMulate.absences WHERE DATE_ADD(NOW(), INTERVAL 7 DAY) >= absence_timestamp
                    AND absence_timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY) OR absent_end_timestamp > NOW() ORDER BY volunteer_id asc',
        'allShiftSql' => 'SELECT * FROM STIMulate.shifts ORDER BY student_id asc',
        'allStreamsSql' => 'SELECT * FROM STIMulate.streams',
        'allStreamStreamsSql' => 'SELECT * FROM STIMulate.facilitator_streams',
        'allStaffSql' => 'SELECT * FROM STIMulate.staff WHERE active =1 ORDER BY staff_name_first asc',
        'allVolunteerSql' => 'SELECT * FROM STIMulate.facilitators WHERE active = 1 ORDER BY stu_name_first asc'];

    // Depending on the action return the appropraite information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case 'manageVolunteer':
            // Execute 3 sql queries and assign them to an associative array to return
            $sqlObject = new \PHP\SqlObject($sqlQueries['allVolunteerSql']);
            $volunteerInformation = $sqlObject->Execute();
            $sqlObject = new \PHP\SqlObject($sqlQueries['allStreamsSql']);
            $streamInformation = $sqlObject->Execute();
            $sqlObject = new \PHP\SqlObject($sqlQueries['allStreamStreamsSql']);
            $volunteerStreamInformation = $sqlObject->Execute();

            // Return associative array
            return array('volunteerInformation' => $volunteerInformation, 'streamInformation' => $streamInformation, 'volunteerStreamInformation' => $volunteerStreamInformation);
        case 'manageStaff':
            // Execute 3 sql queries and assign them to an associative array to return
            $sqlObject = new \PHP\SqlObject($sqlQueries['allStaffSql']);
            $staffInformation = $sqlObject->Execute();

            // Return associative array
            return array('staffInformation' => $staffInformation);
        case 'manageShift':
            // Retreive all shift and stream information
            $sqlObject = new \PHP\SqlObject($sqlQueries['allShiftSql']);
            $shiftInformation = $sqlObject->Execute();
            $sqlObject = new \PHP\SqlObject($sqlQueries['allStreamsSql']);
            $streams = $sqlObject->Execute();

            return array('shiftInformation' => $shiftInformation, 'streamInformation' => $streams);
        case 'manageAbsent':
            $sqlObject = new \PHP\SqlObject($sqlQueries['absentSql']);
            $abscentInformation = $sqlObject->Execute();

            $studentIds = array();
            for($i = 0; $i < count($abscentInformation); $i++){
                array_push($studentIds, $abscentInformation[$i]['volunteer_id']);
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

            return array('absentInformation'=>$abscentInformation,'absentStudentInformation'=>$studentInformation);
        // Default action
        default:
            return false;
    }
}

/*
 * Determines the appropriate html to put into the button control box
 * Echos html into browser
 */
function determineControls(){
    // Define all button options, and unset them as needed using the switch
    $buttonHtml = array('newButton' => '<li class = "admin-controls">Add to All</li>');

    // Depending on the action return the appropraite information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case null:
            // Default action. Display nothing
            $buttonHtml = array();
            break;
        case 'manageAbsent':
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
?>
