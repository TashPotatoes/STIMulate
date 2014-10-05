<?php include 'PHP/functions.php'; ?>
<?php require 'PHP/uac.php'; ?>
<?php $UserAccessControl = new UserAccessControl(); 
    $UserAccessControl->checkTimeout();
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'Include/GlobalHead.inc'; ?>
    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/TimetableInteraction.CSS" rel="stylesheet" type="text/css">
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
    <div class="push-right">
        <?php
            $oneStudent = true;
            include 'Include/Timetable.inc';
        ?>
    </div>
</main>
</body>
</html>