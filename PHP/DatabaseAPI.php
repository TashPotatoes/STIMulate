<?php
require_once "SqlObject.php";

if(isset($_POST["action"])){
    switch($_POST["action"]) {
        case "retrieveAllData":
            RetrieveAllData();
            break;
        case "absent":
            InsertAbsentie($_POST);
            break;
		case "specialisations":
             retrieveAllSpecialisations();
            break;
        case "updatePreferences":
            updateTimetablePreferences($_POST);
            break;
        default:
            echo "Invalid Database Request.";
    }
}

function RetrieveAllData(){
    // Retrieving the initial data from database
    $sqlObject = new \PHP\SqlObject("SELECT * FROM dataaggregator.data;");
    echo JSON_ENCODE($sqlObject->Execute());
}

function InsertAbsentie($post){
    $date = date("d-m-Y", strtotime('this week + '.$post['date'].'days', time()));

    $times = explode(":", $post['time']);
    $dates = explode("-", $date);

    $timestamp = date("Y-m-d H:i:s", mktime($times[0],$times[1], 0, $dates[1], $dates[0], $dates[2]));
    //var_dump($timestamp);
    $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.absences (absence_timestamp, volunteer_id, active) VALUES (:timestamp, :id, 1)", array($timestamp, $post["volunteerID"]));
    $sqlObject->Execute();
    echo JSON_ENCODE(array($timestamp, $post["volunteerID"]));
}

function retrieveAllSpecialisations() {
    $sqlObject = new\PHP\SqlObject("SELECT user_id, specialisations.spec_id, spec_name FROM STIMulate.facilitator_specialisations JOIN specialisations ON facilitator_specialisations.spec_id = specialisations.spec_id");
    $data = $sqlObject->Execute();
    echo JSON_ENCODE($data);
}
    // foreach ($data as $combo) {
    //     if(array_search($combo['spec_name'], $fac_spec_matrix)) {
    //         echo "EXISTS";
    //     } else {
    //         echo "NO EXISTS";
    //     }
    //     //check if spec if in the array
    //     //if is, add user_id to relevant nested array
    //     //if no, add array(spec) to main array, then add user_id to that array
    // }

function retrieveAllSpecialisationsInStreams($inStream) {
    $sqlObject = new\PHP\SqlObject("SELECT `spec_name` FROM specialisations
				WHERE  :instream = 1", array($inStream));
    
    return $sqlObject->Execute();

}



function updateTimetablePreferences($post) {

    $stream = strtoupper($_POST["stream"]);
    $hours = $_POST["max-hour"];
    //$preferences = $_POST["prefs"];
    $student =$_SESSION['user_id'];
    //var_dump($preferences);
	
	$noPref = -50;
	$thirPref = 3;
	$secPref = 9;
	$firstPref = 27;
	$sql = "INSERT INTO STIMulate.preferences (student_id, faculty, 'day', '9', '10', '11', '12', '1', '2', '3', '4') VALUES ";
    
	$daysArray = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY");
	$SHIFTS_IN_DAY = 8;
	$DAYS_IN_WEEK = 5;
	$addRow = false;
	$sqlValues = "";
	for ($i = 0; $i < $DAYS_IN_WEEK; $i++){
		
		for ($j = 0; $j < $SHIFTS_IN_DAY; $j++){
			switch ($_POST["$j $daysArray[$i]"]){
				case $noPref: 
					$sqlValues .= ", " . $noPref;
					break;
				case $thirPref:
					$sqlValues .= ", " . $thirPref;
					$addRow = true;
					break;
				case $secPref:
					$sqlValues .= ", " . $secPref;
					$addRow = true;
					break;
				case $firstPref:
					$sqlValues .= ", " . $firstPref;
					$addRow = true;
					break;
			}
		}
		
		if ($addRow) {
		$sql .= "( '" . $student . "', '" . $stream . "', " . $day . $sqlValues . "), ";
		}
	}
	$sql = rtrim($sql, ",");
	
	
	/*$sql = "INSERT INTO STIMulate.preferences (student_id, faculty, 'day', '9', '10', '11', '12', '1', '2', '3', '4') VALUES (";
    // iterate over each day to generate first half of sql values, then iterate over shifts for last half of sql values
    for ($day = 0; $day < count($preferences); $day++){
		$sql .= " '" . $student . "', '" . $stream . "', " . $day;
		echo "<br/> current sql $sql ";
		for ($shift = 0; $shift < count($preferences[$day]); $shift++){
			$sql .= ", " . $preferences[$day][$shift] . ", ";
		}
	}*/
	
    $sqlObject = new \PHP\SqlObject($sql);
	$sqlObject->Execute();
	echo "<span> Successfully updated for $sql <br/> </span>";
	
}
?>