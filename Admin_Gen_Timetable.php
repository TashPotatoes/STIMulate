<?php
/**
 * Created by PhpStorm.
 * User: Natasha moffat
 * Date: 20/10/2014
 * Time: 4:20 PM
 */

require 'PHP/uac.php';
$UserAccessControl = new UserAccessControl();

if (!$UserAccessControl->isUserLoggedIn() == true) {
    header("Location: Global_Gateway.php");
}
$UserAccessControl->checkTimeout();
?>

<!DOCTYPE html>

<html>
<head>
    <?php include 'Include/Global_Head.inc'; ?>
    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/TimeTableFilter.CSS" rel="stylesheet" type="text/css">
	<link href="CSS/GenTimetable.CSS" rel="stylesheet" type="text/css">
	<script src="JS/Admin_Gen_Timetable.js"></script>
	<meta charset="UTF-8">
	<script src="JS/Automatic/dist/glpk.min.js"></script>	
</head>
<body>
<main>
<?php include 'Include/Global_Page_Head.inc'; ?>
<?php
$currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>";
include 'Include/Global_Breadcrumb.inc';
include 'Include/Global_Sidebar.inc';
include 'Include/Admin_Gen_Timetable.inc';
?>
</main>
</body>
</html>
