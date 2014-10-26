<?php
//Requiring SQL Object
require_once 'PHP/SqlObject.php';

// If there is no get, simply display empty page rather then errors
if(!isset($_GET['action'])){
    $_GET['action'] = null;
}

// If there is both a get and a set handle the data appropriately
if(isset($_POST, $_GET)){
    handlePost();
}

// Calling intial functions to populate page
$sqlResult = RetrieveData();

// If the page is access with an appropraite get variable populate it
if(isset($_GET['action'])) { ?>
<script>
    $(document).ready(function(){
        // Load button interactions, and table sorting controls.
        LoadUserInteractions(<?php echo JSON_ENCODE($_GET['action']); ?>);
    });
</script>
<div class = "push-right">
    <div class = "filter">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Admin Controls</h2>
        </div>
    <span class = "filter-stream">
        <ul>
            <?php
            // Determine buttons to display (New, Manage, etc.)
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
                // Determine the th element of the table and display it.
                determineTableHead();
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            // Insert data retrieved into the table
            insertRows($sqlResult);
            ?>
            </tbody>
        </table>
</div>
<?php }

/*
 * Based on the page get, retrieve the right information and return the data
 * PRE: $_GET['action'] - String denotes the action the function should retrieve
 * POST: Database Information - Array of data from database
 */
function RetrieveData(){
    // Defining the SQL queries to pull data from the database
    $sqlQueries = ['absentSql' => 'SELECT * FROM STIMulate.absences WHERE DATE_ADD(NOW(), INTERVAL 7 DAY) >= absence_timestamp
                    AND absence_timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY) OR absent_end_timestamp > NOW() OR absence_timestamp > NOW() ORDER BY volunteer_id asc',
                    'allShiftSql' => 'SELECT * FROM STIMulate.shifts ORDER BY student_id asc',
                    'allStreamsSql' => 'SELECT * FROM STIMulate.streams',
                    'allStreamStreamsSql' => 'SELECT * FROM STIMulate.facilitator_streams',
                    'allStaffSql' => 'SELECT * FROM STIMulate.staff WHERE active =1 ORDER BY staff_name_first asc',
                    'allVolunteerSql' => 'SELECT * FROM STIMulate.facilitators WHERE active = 1 ORDER BY stu_name_first asc',
                    'allSpecs' => 'SELECT * FROM STIMulate.specialisations'];

    // Depending on the action return the appropriate information
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
            // Retrieve all shift and stream information
            $sqlObject = new \PHP\SqlObject($sqlQueries['allShiftSql']);
            $shiftInformation = $sqlObject->Execute();
            $sqlObject = new \PHP\SqlObject($sqlQueries['allStreamsSql']);
            $streams = $sqlObject->Execute();

            // return associative array
            return array('shiftInformation' => $shiftInformation, 'streamInformation' => $streams);
        case 'manageAbsent':
            // Retrieve all absent information
            $sqlObject = new \PHP\SqlObject($sqlQueries['absentSql']);
            $absentInformation = $sqlObject->Execute();

            // Retrieve student ids from absent data
            $studentIds = array();
            for($i = 0; $i < count($absentInformation); $i++){
                array_push($studentIds, $absentInformation[$i]['volunteer_id']);
            }

            // Generate SQL from student ids
            $retrieveStudentInformation = "SELECT * FROM STIMulate.facilitators WHERE student_id in (";
            for($i = 0; $i < count($studentIds); $i++){
                if($i+1 == count($studentIds)){
                    $retrieveStudentInformation .= ":val".$i;
                } else {
                    $retrieveStudentInformation .= ":val".$i.', ';
                }
            }

            // Order the sql results by acending order
            $retrieveStudentInformation .= ") ORDER BY student_id asc;";

            $sqlObject = new \PHP\SqlObject($retrieveStudentInformation, $studentIds);
            $studentInformation = $sqlObject->Execute();

            // Return associative array
            return array('absentInformation'=>$absentInformation,'absentStudentInformation'=>$studentInformation);
        case 'manageSpecs':
            // Execute 3 sql queries and assign them to an associative array to return
            $sqlObject = new \PHP\SqlObject($sqlQueries['allSpecs']);
            $specInformation = $sqlObject->Execute();
            $sqlObject = new \PHP\SqlObject($sqlQueries['allStreamsSql']);
            $streamInformation = $sqlObject->Execute();


            // Return associative array
            return array('SpecInformation' => $specInformation, 'streamInformation' => $streamInformation);
        // Default action
        default:
            // Do nothing
            return false;
    }
}

/*
 * Determines the appropriate html to put into the button control box
 * Echos html into browser.
 * PRE: $_GET['action'] - String denotes the action of the switch
 * POST: echos in buttons as needed
 */
function determineControls(){
    // Define all button options, and unset them as needed using the switch
    $buttonHtml = array(
        'newButton' => '<li class = "admin-controls">New</li>',
        'manageButton' => '<li class = "admin-controls">Manage</li>',
        'deleteButton' => '<li class = "admin-controls">Delete</li>',
        'resetPasswordButton' => '<li class = "admin-controls large">Reset Password</li>',
        'addCSVButton' => '<li class = "admin-controls">Add by CSV</li>'
        );

    // Depending on the action return the appropraite information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case null:
            // Null action. No buttons, nothing.
            $buttonHtml = array();
            break;
        case 'manageAbsent':
            // Absent page doesn't have add from csv, or reset password buttons
            unset($buttonHtml['addCSVButton'],$buttonHtml['resetPasswordButton']);
            break;
        default:
            // Only staff has reset password so unset it for all but that
            if($_GET['action'] != 'manageStaff'){
                unset($buttonHtml['resetPasswordButton']);
            }
            break;
    }

    // Echo html in as needed
    foreach($buttonHtml as $button){
        echo $button;
    }
}

/*
 * Determines what elements are needed at the top of the table.
 * PRE: $_GET variable set to string
 * POST: Echo in th elements as needed
 */
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
                <th class = "tableHead">Reason<span class = "arrow-down"></span></th>',
                    'manageSpecs' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                <th class = "tableHead">ID<span class = "arrow-down"></span></th>
                <th class = "tableHead">Specialisation<span class = "arrow-down"></span></th>
                <th class = "tableHead">Streams<span class = "arrow-down"></span></th>',
                    );

    // Echo html in as needed
    echo $tableHeadHtml[$_GET['action']];
}

/*
 * Inserts data dynamically depending on data from database
 * PRE: $sqlResult - Array of data from database
 * PRE: $_GET['action'] - string determining what the switch should do
 * POST: Relevant tr and td rows echo'd
 */
function insertRows($sqlResult){
    // Depending on the action return the appropriate information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case 'manageVolunteer':
            // Insert volunteer tr td elements
            insertVolunteer($sqlResult);
            break;
        case 'manageStaff':
            // Insert staff tr td elements
            insertStaff($sqlResult);
            break;
        case 'manageShift':
            //Insert shift tr td elements
            insertShift($sqlResult);
            break;
        case 'manageAbsent':
            //Insert absent tr td elements
            insertAbsent($sqlResult);
            break; 
        case 'manageSpecs':
            //Insert absent tr td elements
            insertSpecs($sqlResult);
            break;
        // Default action. End
        default:
            return false;
    }
}

/*
 * Inserts data in the volunteer database format
 * PRE: $sqlResult - Array data from the volunteer table in the database
 * POST: echo in relevant table data for volunteers
 */
function insertVolunteer($sqlResult){
    // This array is required because of a mismatch that exists
    // between the streams and facilitator_stream tables in the database.
    // This affects echoing indices later
    // facilitator_streams  => [IT, Science, Math, Duty Host]
    // streams              => [Duty Host, IT, Math, Science]
    $streamIndex = array(1, 3, 2, 0);

    // For all volunteer information echo information. Then calculate streams relevant
    for($i = 0; $i < count($sqlResult['volunteerInformation']); $i++){
        echo '<tr>';

        echo '<td class="check-Small"><input type = "checkbox"></td>';

        echo '<td class = "data-item">'.$sqlResult['volunteerInformation'][$i]['student_id'].'</td>';
        echo '<td class = "data-item">'.$sqlResult['volunteerInformation'][$i]['stu_name_first'].' '.$sqlResult['volunteerInformation'][$i]['stu_name_last'].'</td>';
        echo '<td class = "data-item">';

        // Calculate relevant streams and echo in
        for($j = 0; $j < count($sqlResult['volunteerStreamInformation']); $j++) {
            $firstInputted = false;
            /*
             * Depending on how effectively the light reflecting off the moon
             * relative to the sun is hitting the silcon cpu in your computer
             * will determine whether the following will work or not
             */
            // Compare volunteer stream data with volunteer information
            for($k = 1; $k < (count($sqlResult['volunteerStreamInformation'][$j])/2); $k++){
                if($sqlResult['volunteerStreamInformation'][$j][$k] == '1' && $sqlResult['volunteerInformation'][$i]['student_id'] == $sqlResult['volunteerStreamInformation'][$j][0]){
                    // If first inputted add comma else just echo normally.
                    if($firstInputted == false){
                        echo '<span>'.$sqlResult['streamInformation'][$streamIndex[$k-1]]['str_name'].'</span>';
                        $firstInputted = true;
                    } else {
                        echo '<span>, '.$sqlResult['streamInformation'][$streamIndex[$k-1]]['str_name'].'</span>';
                    }
                    echo '<input type = "hidden" name = "'.$sqlResult['streamInformation'][$k-1]['str_name'].'" value = "'.$sqlResult['volunteerStreamInformation'][$j][$k].'">';
                }
            }
        }
        echo '</td>';
        echo '</tr>';
    }
}

function insertSpecs($sqlResult) {
    // This array is required because of a mismatch that exists
    // between the streams and facilitator_stream tables in the database.
    // This affects echoing indices later
    // facilitator_streams  => [IT, Science, Math, Duty Host]
    // streams              => [Duty Host, IT, Math, Science]
    $streamIndex = array(1, 3, 2, 0);

    for($i = 0; $i < count($sqlResult['SpecInformation']); $i++){
        echo '<tr>';
        echo '<td class="check-Small"><input type = "checkbox"></td>';

        echo '<td class = "data-item">'.$sqlResult['SpecInformation'][$i]['spec_id'].'</td>';
        echo '<td class = "data-item">'.$sqlResult['SpecInformation'][$i]['spec_name'].'</td>';
        echo '<td class = "data-item">'.$sqlResult['SpecInformation'][$i]['in_it'].$sqlResult['SpecInformation'][$i]['in_sc'].$sqlResult['SpecInformation'][$i]['in_ma'].'</td>';
        
        echo '</tr>';
    }
}
/*
 * Inserts staff information as per a successful staff database
 * retrieve
 * PRE: $sqlResult - array Array of staff information in staff format
 * POST: Echo in staff table data
 */
function insertStaff($sqlResult){
    // For each row echo in the relevant information in td format
    for($i = 0; $i < count($sqlResult['staffInformation']); $i++){
        echo '<tr>';
        echo '<td class="check-Small"><input type = "checkbox"></td>';

        echo '<td class = "data-item">'.$sqlResult['staffInformation'][$i]['staff_id'].'</td>';
        echo '<td class = "data-item">'.$sqlResult['staffInformation'][$i]['staff_name_first'].' '.$sqlResult['staffInformation'][$i]['staff_name_last'].'</td>';
        echo '</tr>';
    }
}

/*
 * Inserts shift information as per a successful shift database
 * retrieve
 * PRE: $sqlResult - array Array of shift information in shift format
 * POST: Echo in shift table data
 */
function insertShift($sqlResult) {
    // Day array to convert index to day
    $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");

    // For each shift calculate proper time and input into table
    for ($i = 0; $i < count($sqlResult['shiftInformation']); $i++) {
        $time = $sqlResult['shiftInformation'][$i]['shi_time'];

        // If time is 13:00 turn it into 1:00pm. Similarly 9:00 becomes 9:00am
        // If shift time > 12:00 24-hour time
        if (date("H:i", strtotime($sqlResult['shiftInformation'][$i]['shi_time'])) > date("H:i", strtotime("12:00"))) {
            // Shift time - 12 concatenate pm on the end
            $sqlResult['shiftInformation'][$i]['shi_time'] = date("H:i", strtotime($time.' -12 hours')).'pm';
        } else {
            // Shift time - 12 concatenate pm on the end
            $sqlResult['shiftInformation'][$i]['shi_time'] = $time.'am';
        }

        // If stream id matches shift stream id then define the stream name as a variable
        for ($j = 0; $j < count($sqlResult['streamInformation']); $j++) {
            if ($sqlResult['streamInformation'][$j]['stream_id'] == $sqlResult['shiftInformation'][$i]['shi_stream']) {
                $stream = $sqlResult['streamInformation'][$j]['str_name'];
            } else {
                $stream = '';
            }
        }

        // Simply echo in html
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

/*
 * Insert absent html into webpage
 * PRE: $sqlResult - Array array of sql results of absent table format
 * POST: echo in td and tr of sql result data
 */
function insertAbsent($sqlResult) {
    // For each absent, and student record if the ids match, then echo in the data to the table
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

/*
 * Handles post requests to the page
 * PRE: $_GET['action'] - String a string to denote the function of the page
 * PRE: $_POST['type'] - String a string to denote the action of the get.
 */
function handlePost(){
    // Handle get relevant actions
    switch($_GET['action']){
        case 'manageVolunteer':
            // Handle volunteer related requests
            manageVolunteer();
            break;
        case 'manageStaff':
            // If handle reseting password else handle all staff requires
            if(isset($_POST['type'])){
                if($_POST['type'] == 'resetPassword'){
                    resetPassword();
                } else {
                    manageStaff();
                }
            }
            break;
        case 'manageShift':
            // Handle shift related requests
            manageShift();
            break;
        case 'manageAbsent':
            // Handle absent related requests
            manageAbsent();
            break;
        default:
            // Do nothing
            break;
    }
}

/*
 * Handles post request to the page that of of an absent
 * based nature
 * PRE: $_POST['type'] - string type of action to perform - [all]
 * PRE: $_POST['time'] - string timestamp in html format - type[all]
 * PRE: $_POST['endTime'] - string timestamp in html format - type[all]
 * PRE: $_POST['newID'] - string student ID to be changed in absent table - type[manage]
 * PRE: $_POST['iD'] - string student ID unchanged to be inserted with new request - type[new]
 * PRE: $_POST['absentID'] - string absent database id - type[manage, delete]
 * PRE: $_POST['Reason'] - string reason why volunteer was absent - type[all]
 * POST: Absent data in database
 */
function manageAbsent()
{
    // If post with type recieved calculate start and end time in PHP timedate class sql format
    if (isset($_POST['type'])) {
        $time = date("Y-m-d H:i:s", strtotime($_POST['time']));
        if($_POST['endTime']!='') {
            $end = date("Y-m-d H:i:s", strtotime($_POST['endTime']));
        } else {
            $end = null;
        }

        // If new add to database, manage update database, delete remove row.
        switch ($_POST['type']) {
            case 'new':
                // Add new absent record
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.absences
                  (absence_timestamp, absent_end_timestamp, volunteer_id, reason) VALUES (:timestamp, :absent_end_timestamp, :id, :reason)", array($time, $end, $_POST['id'], $_POST['reason']));
                $sqlObject->Execute();
                break;
            case 'manage':
                // Update existing record in database
                $updateQuery = "UPDATE STIMulate.absences SET absence_timestamp = :absence_timestamp, absent_end_timestamp  = :absent_end_timestamp,
                                volunteer_id = :volunteer_id, reason = :reason WHERE absence_id = :absence_id";
                $sqlObject = new \PHP\SqlObject($updateQuery, array($time, $end, $_POST['newId'], $_POST['reason'], $_POST['absentId']));
                $sqlObject->Execute();
                break;
            case 'delete':
                // Remove existing record from database.
                // Generate query based on the number of absent ids to remove multiple
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
                // Do nothing
                break;
        }
    }
}

/*
 * Handles post request of a shift based
 * nature
 * PRE: $_POST['type'] - string type of action to perform - [all]
 * PRE: $_POST['stream'] - string english word for stream - [all]
 * PRE: $_POST['id'] - string id for student - type[new]
 * PRE: $_POST['day'] - string english word for day - type[all]
 * PRE: $_POST['duration'] - string or int duration (1,2,3) for shift - type[new, manage]
 * PRE: $_POST['newID'] - string new student id to be assigned to shift id - type[manage]
 * PRE: $_POST['shiftID'] - string shift id of shift row in database - type[manage, delete]
 * POST: Shift data in database
 */
function manageShift()
{
    // if post type is set, get streamID, day index, and perform action
    if (isset($_POST['type'])) {
        $streamID = '';

        // Get stream index
        if(isset($_POST['stream'])) {
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

            //  Get day index
            $dayIndex = date("w", strtotime($_POST['day'])) - 1;
        }
        switch ($_POST['type']) {
            case 'new':
                // Add shift to database
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.shifts (student_id, shi_stream, shi_day, shi_time,
                                            shi_duration) VALUES (:id, :stream, :day, :time, :duration);", array($_POST['id'], $streamID, $dayIndex, $_POST['time'], $_POST['duration']));
                $sqlObject->Execute();
                break;
            case 'manage':
                // Update shift information to database
                $updateQuery = "UPDATE STIMulate.shifts SET student_id = :student_id, shi_stream = :shi_stream, shi_day = :shi_day, shi_time = :shi_time,
                                            shi_duration = :shi_duration WHERE shift_id = :shift_id";

                $sqlObject = new \PHP\SqlObject($updateQuery, array($_POST['newId'], $streamID, $dayIndex, $_POST['time'], $_POST['duration'], $_POST['shiftID']));
                $sqlObject->Execute();
                break;
            case 'delete':
                // Remove shift from database
                // Generate shift query based on shift_ids provided
                $deleteQuery = "DELETE FROM STIMulate.shifts WHERE shift_id IN (";
                for ($i = 0; $i < count($_POST['shift_id']); $i++) {
                    if ($i + 1 == count($_POST['shift_id'])) {
                        $deleteQuery .= ":val" . $i;
                    } else {
                        $deleteQuery .= ":val" . $i . ', ';
                    }
                }

                // Execute query
                $deleteQuery .= ");";
                $sqlObject = new \PHP\SqlObject($deleteQuery, $_POST['shift_id']);
                $sqlObject->Execute();
                break;
            default:
                // Do nothing
                break;
        }
    }
}

/*
 * Manage post request of a staff based nature
 * PRE: $_POST['type'] - string type of action to perform - [all]
 * PRE: $_POST['id'] - string id for staff - [all]
 * PRE: $_POST['name'] - string name of staff (Firstname lastname lastname-lastname) - [all]
 * PRE: $_POST['password'] - string Staff password validated - type[new]
 * PRE: $_POST['newID'] - string New staff id - type[manage]
 * POST: Shift data in database
 */
function manageStaff()
{
    // If valid post
    if (isset($_POST['id'], $_POST['type'])) {
        // If name posted calculate first and last name
        if (isset($_POST['name'])) {
            $name = explode(' ', $_POST['name']); // Split at spaces
            $first = $name[0];

            // 2nd element forward are last names
            if (isset($name[1])) {
                $last = '';
                for($i = 1; $i < count ($name); $i++){
                    $last .= $name[$i];
                }
            // Last name doesn't exist. Blank entry
            } else {
                $last = " ";
            }
        }

        switch ($_POST['type']) {
            case 'new':
                // New data to database
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.staff (staff_id, staff_name_first,
              staff_name_last, staff_password, active)
                VALUES (:id, :name, :last, MD5(:pass), 1);", array($_POST['id'], $first, $last, $_POST['password']));
                $sqlObject->Execute();
                break;
            case 'manage':
                // Update existing data
                $sqlObject = new \PHP\SqlObject("UPDATE STIMulate.staff
                SET staff_id = :newId, staff_name_first = :firstname, staff_name_last = :lastname
                WHERE staff_id = :id;", array($_POST['newId'], $first, $last, $_POST['id']));
                $sqlObject->Execute();
                break;
            case 'delete':
                // Remove existing data
                // Generate sql query based on staff ids posted
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

                // Execute
                $sqlObject = new \PHP\SqlObject($deleteQuery, $_POST['id']);
                $sqlObject->Execute();
                break;
            default:
                // Do nothing
                break;
        }
    }
}

/*
 * Handles post request of volunteer nature
 */
function manageVolunteer()
{
    // If valid post
    if (isset($_POST['id'], $_POST['type'])) {
        // If name posted calculate first and last name
        if (isset($_POST['name'])) {
            $name = explode(' ', $_POST['name']); // Explode at ' '
            $first = $name[0];

            // 2nd element onward is lastname
            if (isset($name[1])) {
                $last = '';
                for($i = 1; $i < count ($name); $i++){
                    $last .= $name[$i].' ';
                }
            // Otherwise last name is empty
            } else {
                $last = "";
            }
        }
        switch ($_POST['type']) {
            case 'new':
                // Add new facilitator
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.facilitators (student_id, stu_name_first, stu_name_last, active)
                                                        VALUES (:id, :name, :last, 1);", array($_POST['id'], $first, $last));
                $sqlObject->Execute();

                // Adding stream data
                if(!isset($_POST['it'])){$_POST['it'] = 0; }else{$_POST['it'] = 1;}
                if(!isset($_POST['math'])){$_POST['math'] = 0; }else{$_POST['math'] = 1;}
                if(!isset($_POST['science'])){$_POST['science'] = 0; }else{$_POST['science'] = 1;}
                if(!isset($_POST['dutyHost'])){$_POST['dutyHost'] = 0; }else{$_POST['dutyHost'] = 1;}

                // Adding stream data
                $sql = "INSERT INTO STIMulate.facilitator_streams (user_id, is_it, is_sc, is_ma, is_dh)
                                                        VALUES (:id, :it, :sc, :ma, :dh);";
                $sqlObject = new \PHP\SqlObject($sql, array($_POST['id'], $_POST['it'], $_POST['math'], $_POST['science'], $_POST['dutyHost']));
                $sqlObject->Execute();

                break;
            case 'manage':
                // Updating facilitator and stream data
                $sqlObject = new \PHP\SqlObject("UPDATE STIMulate.facilitators
                                                            SET student_id = :newId, stu_name_first = :firstname, stu_name_last = :lastname
                                                            WHERE student_id = :id;", array($_POST['newId'], $first, $last, $_POST['id']));
                $sqlObject->Execute();

                // updating stream data. If non-existant, add new record.
                $updateStream = "INSERT INTO STIMulate.facilitator_streams (user_id, is_it, is_sc, is_ma, is_dh)
                                VALUES (:id, :it, :sc, :ma, :dh)
                                ON DUPLICATE KEY
                                UPDATE is_it = :it, is_sc = :sc, is_ma = :ma, is_dh = :dh;";

                // updating stream data
                if(!isset($_POST['it'])){$_POST['it'] = 0; }else{$_POST['it'] = 1;}
                if(!isset($_POST['math'])){$_POST['math'] = 0; }else{$_POST['math'] = 1;}
                if(!isset($_POST['science'])){$_POST['science'] = 0; }else{$_POST['science'] = 1;}
                if(!isset($_POST['dutyHost'])){$_POST['dutyHost'] = 0; }else{$_POST['dutyHost'] = 1;}

                $sqlObject = new \PHP\SqlObject($updateStream, array($_POST['id'], $_POST['it'], $_POST['science'], $_POST['math'], $_POST['dutyHost']));
                $sqlObject->Execute();

                break;
            case 'delete':
                // Remove facilitator data
                // Generate sql query based on volunteer ids
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

function manageSpecs() {
    // If valid post
    if (isset($_POST['id'], $_POST['type'])) {
        // If name posted calculate first and last name
        if (isset($_POST['name'])) {
            $name = explode(' ', $_POST['name']); // Explode at ' '
            $first = $name[0];

            // 2nd element onward is lastname
            if (isset($name[1])) {
                $last = '';
                for($i = 1; $i < count ($name); $i++){
                    $last .= $name[$i].' ';
                }
            // Otherwise last name is empty
            } else {
                $last = "";
            }
        }
        switch ($_POST['type']) {
            case 'new':
                // Add new facilitator
                $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.specialisations (spec_id, spec_name, in_it, in_sc, in_ma)
                                                        VALUES (:id, :name, :in_it, :in_sc, :in_ma);", array($_POST['id'], $_POST['name'], $_POST['in_it'], $_POST['in_sc'],$_POST['in_ma']));
                $sqlObject->Execute();

                // Adding stream data
                if(!isset($_POST['it'])){$_POST['it'] = 0; }else{$_POST['it'] = 1;}
                if(!isset($_POST['math'])){$_POST['math'] = 0; }else{$_POST['math'] = 1;}
                if(!isset($_POST['science'])){$_POST['science'] = 0; }else{$_POST['science'] = 1;}
                if(!isset($_POST['dutyHost'])){$_POST['dutyHost'] = 0; }else{$_POST['dutyHost'] = 1;}

            case 'manage':
                // Updating facilitator and stream data
                $sqlObject = new \PHP\SqlObject("UPDATE STIMulate.specialisations
                                                            SET spec_id = :newId, spec_name = :name, in_it = :in_it, in_sc = :in_sc, in_ma = :in_ma
                                                            WHERE spec_id = :id;", array($_POST['id'], $_POST['name'], $_POST['in_it'], $_POST['in_sc'],$_POST['in_ma']));
                $sqlObject->Execute();

                // updating stream data. If non-existant, add new record.
                $updateStream = "INSERT INTO STIMulate.specialisations (spec_id, spec_name, in_it, in_sc, in_ma)
                                VALUES (:id, :name, :it, :sc, :ma)
                                ON DUPLICATE KEY
                                UPDATE is_it = :it, is_sc = :sc, is_ma = :ma, is_dh = :dh;";

                // updating stream data
                if(!isset($_POST['it'])){$_POST['it'] = 0; }else{$_POST['it'] = 1;}
                if(!isset($_POST['math'])){$_POST['math'] = 0; }else{$_POST['math'] = 1;}
                if(!isset($_POST['science'])){$_POST['science'] = 0; }else{$_POST['science'] = 1;}

                $sqlObject = new \PHP\SqlObject($updateStream, array($_POST['spec_id'], $_POST['spec_name'], $_POST['it'], $_POST['science'], $_POST['math'], ));
                $sqlObject->Execute();

                break;
            case 'delete':
                // Remove facilitator data
                // Generate sql query based on volunteer ids
                $deleteQuery = "DELETE FROM STIMulate.specialisations
                                        WHERE spec_id IN (";
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

/*
 * Resets staff passwords based on which staff were provided
 * PRE: $_POST['id'] - Array Staff ids in array format to be reset
 * PRE: $_POST['password'] - Password to reset them too
 * POST: Staff password reset.
 */
function resetPassword(){
    $sql = 'UPDATE STIMulate.staff SET staff_password = MD5(:password), passReset = 1 WHERE staff_id in (';

    // for each id posted generate sql
    for ($i = 0; $i < count($_POST['id']); $i++) {
        if ($i + 1 == count($_POST['id'])) {
            $sql .= ":val" . $i;
        } else {
            $sql .= ":val" . $i . ', ';
        }
    }

    $sql .= ");";
    $sqlObject = new \PHP\SqlObject($sql, array_merge(array($_POST['password']), $_POST['id']));
    $sqlObject->Execute();
}
?>
