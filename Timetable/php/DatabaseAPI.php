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
    $dateNow = date("Y-m-d", strtotime("now")+mktime(0,0,0,date('n'),$date, date('y')));
    var_dump($dateNow);
    $hourMinuteSecond = explode(":", $time);
    $dayMonthYear = explode("-", $date);
    $date = date("Y-m-d H:i:s", mktime($hourMinuteSecond[0], $hourMinuteSecond[1], $hourMinuteSecond[2], date('n'), $date, date('y')));
    $sqlObject = new \PHP\SqlObject("INSERT INTO stimulate.volunteerabsenties (timestamp, volunteerID) VALUES (:timestamp, :volunteerID)", array($date, $volunteerID));
    $sqlObject->Execute();
}

function amazonPush() {
	$rand = substr(md5(microtime()), rand(0,26),5);
	$sqlObject = new \php\SqlObject("INSERT INTO STIMulate.connection_test (test_code) VALUES(:code)", $rand);
	//$sqlObject->bindParam(':code',$rand);
	$sqlObject->execute();
}
function amazonPull() {
	$sqlObject = new \php\SqlObject("SELECT `test_code` FROM STIMulate.connection_test");
	$data = $sqlObject->execute();
	$data = array_map('reset', $data);
	foreach ($data as $d => $data[1]) {
		echo $d.": ".$data[1] . ".  ";
	}
}

?>