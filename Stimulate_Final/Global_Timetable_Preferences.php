<?php require 'PHP/functions.php'; ?>
<?php require_once "PHP/SqlObject.php"; ?>
<?php require 'PHP/uac.php'; ?>
<?php
    $pageTitle = "Availabilities";
	$UserAccessControl = new UserAccessControl();
    $UserAccessControl->checkTimeout();
?>

<!--
Author: Pearl Gariano
-->

<!DOCTYPE HTML>
<html>
	<head>
		<title>Timetable Preferences</title>
		<!--<link rel="stylesheet" type="text/css" href="CSS/Inputcolumn-timetable.css"> -->
	    <?php include 'Include/Global_Head.inc'; ?>
	    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
	    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
	    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
	    <script src = "js/ColourChange.js"></script>
	    <script src = "js/TimetableInteraction.js"></script>
	</head>
<body>
<main>
    <?php include 'Include/Global_Page_Head.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>
                    <p> > Timetable Preferences</p>";
        include 'Include/Global_Breadcrumb.inc'; ?>
    <?php include 'Include/Global_Sidebar.inc'; ?>
    <?php include 'Include/Volunteer_Timetable_Preferences.inc'; ?>
</main>
</body>
		
	</body>
</html>