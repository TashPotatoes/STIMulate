<?php
    require_once '/php/databaseAPI.php';
    require_once '/php/SqlObject.php';

    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
?>

<!DOCTYPE html>

<html>
<head>
    <?php include 'Include/GlobalHead.inc'; ?>
        <?php include 'PHP/functions.php'; ?>
    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
    <script src = "js/filters.js"></script>
</head>
<body>
<main>

    <?php include 'Include/Timetable.inc'; ?>

</main>
    </body>
</html>