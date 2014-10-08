<?php
    /* Importing Classes */
    require_once 'PHP/SqlObject.php';

    // Retrieving Data
    $information = RetrieveIntialData();

?>


<div class = "push-right">
    <div id = "dashboard">
        <div class = "notification">
            <p>(IT) Hayden Jackson is asbent Tuesday, at 12:00pm</p>
            <p>(IT) Anthony Price is asbent Wednesday, at 1:00pm</p>
            <p>11:00am</p>
        </div>
        <div class = "notification">
            <p>Louis Van Dyke has reached 20 hours!</p>
            <p>10:00am</p>
        </div>
    </div>
</div>

<?php
    /* PAGE SPECIFIC FUNCTIONS */
    function RetrieveIntialData(){
        // Sql queries
        $absentieQuery = "SELECT * FROM STIMulate.absences WHERE DATE_ADD(NOW(), INTERVAL 7 DAY) >= absence_timestamp;";
        $studentInformationQuery = "SELECT * FROM STIMulate.facilitators WHERE student_id IN (";

        // Retrieving Absent information
        $sqlObject = new \PHP\SqlObject($absentieQuery);
        $absentInformation = $sqlObject->Execute();

        // Retrieving Student Informaton
        $studentNumbers = array();
        for($i = 0; $i < count($absentInformation); $i++){
            array_push($studentNumbers, $absentInformation[$i]['volunteer_id']);
            $studentInformationQuery .= ":val".$i;
        }
        $studentInformationQuery .= ");";

        $sqlObject = new \PHP\SqlObject($studentInformationQuery, $studentNumbers);
        $studentInformation = $sqlObject->Execute();

        return array("absent" => $absentInformation, "student" =>$studentInformation);
    }