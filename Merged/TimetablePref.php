<?php require 'PHP/functions.php'; ?>
<?php require 'PHP/uac.php'; ?>
<?php $UserAccessControl = new UserAccessControl();
    require_once "PHP/SqlObject.php";
?>

<!--
Author: Pearl Gariano
-->

<!DOCTYPE HTML>
<html>
	<head>
		<title>Timetable Preferences</title>
		<!--<link rel="stylesheet" type="text/css" href="CSS/Inputcolumn-timetable.css"> -->
	    <?php include 'Include/GlobalHead.inc'; ?>
	    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
	    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
	    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
	    <script src = "js/ColourChange.js"></script>
	</head>
<body>
<main>
    <?php include 'Include/Header.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>
                    <p> > Timetable Preferences</p>";
        include 'Include/LocationSeparator.inc'; ?>
    <?php include 'Include/SideBar.inc'; ?>
    <?php include 'Include/TimetablePreferences.inc'; ?>
</main>
</body>
		
	</body>
</html>