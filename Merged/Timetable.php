<?php
/**
 * Created by PhpStorm.
 * User: Hayden
 * Date: 16/09/14
 * Time: 7:09 PM
 */
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'Include/GlobalHead.inc'; ?>
    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/Absenses.CSS" rel="stylesheet" type="text/css">
    <script src = "js/filters.js"></script>
    <script src = "js/TimetableInteraction.js"></script>
</head>
<body>
<main>
    <?php include 'Include/Header.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>
                    <p> > Timetable</p>";
        include 'Include/LocationSeparator.inc'; ?>
    <?php include 'Include/SideBar.inc'; ?>
    <?php include 'Include/Timetable.inc'; ?>

</main>
</body>
</html>