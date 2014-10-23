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

    $stream = $post["stream"];
    $hours = $post["hours"];
    $preferences = $post["array"];
    //$student = array($_SESSION['user_id']);
    
    $query = new \PHP\SqlObject("INSERT INTO STIMulate.preferences (student_id, faculty, 'day', '9', '10', '11', '12', '1', '2', '3', '4')
                    VALUES 'n12345678', $stream, 0, $preferences[0][0], $preferences[0][1],
                    $preferences[0][2], $preferences[0][3], $preferences[0][4], $preferences[0][5],
                    $preferences[0][6], $preferences[0][7]");
    

    
}