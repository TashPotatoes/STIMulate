<?php
require_once 'PHP/SqlObject.php';
    if(isset($_POST)){
        if(isset($_POST['timestamp'],$_POST['reason'], $_SESSION['user_id'], $_POST['shift'])){
            $time = date("Y-m-d H:i:s", strtotime($_POST['timestamp']));

            $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.absences (absence_timestamp, volunteer_id, reason) VALUES (:timestamp, :id, :reason)", array($time, $_SESSION['user_id'], $_POST['reason']));
            //$sqlObject->Execute();
        } else if(isset($_POST['timestamp'],$_POST['reason'], $_SESSION['user_id'], $_POST['timestampEnd'])) {
            $startTime = date("Y-m-d H:i:s", strtotime($_POST['timestamp']));
            $endTime = date("Y-m-d H:i:s", strtotime($_POST['timestampEnd'].'+ 1 day'));

            $sqlObject = new \PHP\SqlObject("INSERT INTO STIMulate.absences (absence_timestamp, absent_end_timestamp, volunteer_id, reason) VALUES (:timestamp, :endTime, :id, :reason)", array($startTime, $endTime, $_SESSION['user_id'], $_POST['reason']));
            $sqlObject->Execute();
        }
    }
    date_default_timezone_set('Australia/Sydney');
    $sqlObject = new \PHP\SqlObject("SELECT * FROM STIMulate.shifts WHERE student_id = :student_id", array($_SESSION['user_id']));
    $shiftInformation = $sqlObject->Execute();

    $DAY_OF_WEEK = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
?>

<div class = "push-right">
    <div class = "contentWrapper">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Record Absence</h2>
        </div>

        <form method = "POST" action = "" id = "absentieForm">
            <div id = "dateWrapper">
                <label>Date:</label>
                <?php echo '<input type = "date" value = "'.date('Y-m-d', time()).'T'.date('H:i', time()).'" name = "timestamp" class = "entryFields">' ?>
                <span class = "small interactive-text">+ Add end date</span>
            </div>
            <div id = "timeWrapper">
                <label>Time:</label>
                    <?php
                    if(isset($shiftInformation)){
                        if(count($shiftInformation) > 0){
                            echo '<select name = "shift">';
                            for($i = 0; $i < count($shiftInformation); $i++) {
                                $time = date("d/m/y", strtotime($DAY_OF_WEEK[$shiftInformation[$i]['shi_day']])); //'.$time.' - ' .' '.$DAY_OF_WEEK[$shiftInformation[$i]['shi_day']].
                                echo '<option>'.$shiftInformation[$i]['shi_time'].'</option>';
                            }
                            echo '</select>';
                        }
                    }
                    ?>


            </div>
            <div id = "reasonWrapper">
                <label>Reason:</label>
                <textarea placeholder="Reason..." name = "reason"></textarea>
            </div>
            <div id = "buttonWrapper">
                <input type = "submit" class = "submitButton">
                <input type = "button" class = "submitButton" value = "Cancel">
            </div>
        </form>
    </div>
</div>