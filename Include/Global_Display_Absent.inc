<?php
    /* Importing Classes */
    require_once 'PHP/SqlObject.php';

    // Retrieving Data
    $information = RetrieveIntialDataDash();
?>

<div id = "dashWrapper">
    <div class = "headElement">
        <img src="IMG/calander.png" alt="Calander" class = "inline-image">
        <h2 class = "inline-text">Absent Facilitators</h2>
    </div>
    <div id = "dashboard">
    <?php
    // Inserts Absent notification into HTML
    InsertAbsentNotification($information);
    ?>
    </div>
</div>



<?php
    /*
     * Retrieves all necessary information to display information
     *
     */
    function RetrieveIntialDataDash(){
        // Sql queries
        $absentieQuery = "SELECT * FROM STIMulate.absences WHERE DATE_ADD(NOW(), INTERVAL 7 DAY) >= absence_timestamp
                    AND absence_timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY) OR absent_end_timestamp > NOW() OR absence_timestamp > NOW() ORDER BY absence_timestamp asc;";
        $studentInformationQuery = "SELECT * FROM STIMulate.facilitators WHERE student_id IN (";

        // Retrieving Absent information
        $sqlObject = new \PHP\SqlObject($absentieQuery);
        $absentInformation = $sqlObject->Execute();

        // Retrieving Student Informaton
        $studentNumbers = array();

        // Select relevant student data based on the absent student data
        for($i = 0; $i < count($absentInformation); $i++){
            array_push($studentNumbers, $absentInformation[$i]['volunteer_id']);
            if($i+1 == count($absentInformation)){
                $studentInformationQuery .= ":val".$i;
            } else {
                $studentInformationQuery .= ":val".$i.', ';
            }
        }
        $studentInformationQuery .= ");";

        // Executing database query
        $sqlObject = new \PHP\SqlObject($studentInformationQuery, $studentNumbers);
        $studentInformation = $sqlObject->Execute();

        return array("absent" => $absentInformation, "student" =>$studentInformation);
    }

function InsertAbsentNotification($information){
    // Foreach absent and corresponding student record
    for($i = 0; $i < count($information['absent']); $i++) {

        // Retrieving day and time information based off of absent information
        $dayAbsent = date("l", strtotime($information['absent'][$i]['absence_timestamp']));
        $absentDate = date("d/m/Y", strtotime($information['absent'][$i]['absence_timestamp']));
        $timeAbsent = TimeToAMPM(date("h:i", strtotime($information['absent'][$i]['absence_timestamp'])));
        if(isset($information['absent'][$i]['absent_end_timestamp'])){
            $endDayAbsent = date("l", strtotime($information['absent'][$i]['absent_end_timestamp']));
            $endAbsentDate = date("d/m/Y", strtotime($information['absent'][$i]['absent_end_timestamp']));
        }

        // Echoing student name, day and time into dash
        for($j = 0; $j < count($information['student']); $j++){
            if($information['absent'][$i]['volunteer_id'] == $information['student'][$j]['student_id']){
                $html = '<div class = "notification">
                    <p>'.$information['student'][$j]['stu_name_first'].' '.$information['student'][$j]['stu_name_last'].' is absent '.$dayAbsent.' '.$absentDate.'';
                if(isset($information['absent'][$i]['absent_end_timestamp'])){
                    $html.= ' to '.$endDayAbsent.' the '.$endAbsentDate;

                } else {
                    $html.=', at '.$timeAbsent;
                }
                $html .= '</p></div>';
                echo $html;
            }
        }
    }
}

// Temp to add am or pm to times
function TimeToAMPM($time){
    if($time < date("h:i", strtotime("09:00"))) {
        $time .= 'pm';
    } else {
        $time .= 'am';
    }
    return $time;
}