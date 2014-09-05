<?php
/**
 * Created by PhpStorm.
 * User: crazygravy89
 * Date: 31/07/14
 * Time: 2:17 PM
 */

require_once "SqlObject.php";
if(isset($_POST['action'])) {
    switch($_POST['action']){
        case "absent":
            InsertAbsent($_POST['volunteerID'], $_POST["date"], $_POST["time"]);
    }

}

function InsertAbsent($volunteerID, $date, $time){
    $hourMinuteSecond = explode(":", $time);

    $date = date("Y-m-d H:i:s", mktime($hourMinuteSecond[0], $hourMinuteSecond[1], $hourMinuteSecond[2], date('n'), $date, date('y')));
    $sqlObject = new \PHP\SqlObject("INSERT INTO stimulate.volunteerabsenties (timestamp, volunteerID) VALUES (:timestamp, :volunteerID)", array($date, $volunteerID));
    $sqlObject->Execute();
}

