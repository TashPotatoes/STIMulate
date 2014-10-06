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
    $sqlObject = new\PHP\SqlObject("SELECT * FROM STIMulate.facilitator_specialisations
                                    INNER JOIN specialisations
                                    ON facilitator_specialisations.spec_id=specialisations.spec_id");
    
    return $sqlObject->Execute();
}