<?php
    require_once '/php/SqlObject.php';

    $array = $_POST['array'];
    $student_id = $_SESSION['user_id'];
    $stream = $_POST['stream'];
    $hours = $_POST['hours'];

    print_r($stream);

    //$query = new \PHP\SqlObject(INSERT INTO STIMulate.preferences (student_id, faculty, `day`, `9`, `10`, `11`, `12`, `1`, `2`, `3`, `4`) VALUES (array($_SESSION['user_id']), 'stream', 'MONDAY', '3', '2', '1', '1', '0', '0', '0', '0');
?>

