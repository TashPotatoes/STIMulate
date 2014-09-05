<?php
/**
 * Created by PhpStorm.
 * User: crazygravy89
 * Date: 31/07/14
 * Time: 2:17 PM
 */

require_once 'NFC.php';
require_once 'Student.php';
require_once "SqlObject.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {
    switch($_POST['action']){
        case 'refresh':
            $nfcObject = new \PHP\NFC($_POST['readerID']);
            $nfcInformation = $nfcObject->GetReaderData();

            if(isset($nfcInformation)) {
                if(count($nfcInformation) > 0){
                    $studentInfo = new \PHP\Student($nfcInformation[0]["studentTag"]);
                    $jsonInformation = $studentInfo->GetStudentInformation();

                    if($jsonInformation == null) {
                        $jsonInformation[0]["newStudent"] = true;
                        $jsonInformation[0]["nfcTag"] = $nfcInformation[0]["studentTag"];
                    }

                    $jsonInformation[0]["TimeStamp"] = $nfcInformation[0]["TimeStamp"];

                    echo JSON_ENCODE($jsonInformation);
                }
            }else {
                echo ($nfcInformation);
            }
            break;
        case 'facilitator':
            $sqlObject = new \PHP\SqlObject("SELECT students.name FROM stimulate.students WHERE students.studentNumber LIKE '%';", array());
            echo JSON_ENCODE($sqlObject->Execute());
            break;
        case "absent":
            InsertAbsent($_POST['volunteerID'], $_POST["date"], $_POST["time"]);
            break;
        default:
            break;
    }
} else if(isset($_POST['android'])) {
    $sqlObject = new \PHP\SqlObject("INSERT INTO stimulate.test (response) VALUES (:response)", array($_POST['android']));
    $sqlObject->Execute();

}

function InsertAbsent($volunteerID, $date, $time){
    $hourMinuteSecond = explode(":", $time);

    $date = date("Y-m-d H:i:s", mktime($hourMinuteSecond[0], $hourMinuteSecond[1], $hourMinuteSecond[2], date('n'), $date, date('y')));
    $sqlObject = new \PHP\SqlObject("INSERT INTO stimulate.volunteerabsenties (timestamp, volunteerID) VALUES (:timestamp, :volunteerID)", array($date, $volunteerID));
    $sqlObject->Execute();
}


