<?php require '/php/databaseAPI.php'; ?>
<?php require_once '/php/SqlObject.php'; ?>
<?php require 'PHP/uac.php'; ?>
<?php
    $UserAccessControl = new UserAccessControl(); 
?>

<!DOCTYPE html>

<html>
<head>
    <?php include 'Include/Global_Head.inc'; ?>
        <?php include 'PHP/functions.php'; ?>
    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/TimeTableFilter.CSS" rel="stylesheet" type="text/css">
    <script src = "js/filters.js"></script>
</head>
<body>
    <main>
        <div class="index-container">
        <div class="messagebox" id="defaultLogo">
        <img src="IMG/QUT.png" vertical-aign="middle" alt="QUT Logo" class = "inline-image">
        <h1 class = inline-text>STIMulate</h1>
        </div>
        <?php include 'Include/Global_Timetable.inc'; ?>
        <div class="messagebox" >
            <p>
            <a href="Volunteer_Shifts.php">Facilitator Login</a>
            </p>
        </div>
        </div>
    </main>
    </body>
</html>