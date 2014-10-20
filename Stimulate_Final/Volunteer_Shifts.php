<?php include 'PHP/functions.php'; ?>
<?php require 'PHP/uac.php'; ?>
<?php $UserAccessControl = new UserAccessControl(); 
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
    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/TimetableInteraction.CSS" rel="stylesheet" type="text/css">
    <script src = "JS/filters.js"></script>
    <script src = "JS/TimetableInteraction.js"></script>
</head>
<body>
<main>
    <?php include 'Include/Global_Page_Head.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>
                    <p> > Timetable</p>";
        include 'Include/Global_Breadcrumb.inc'; ?>
    <?php include 'Include/Global_Sidebar.inc'; ?>
    <div class="push-right">
        <?php
            $oneStudent = true;
            include 'Include/Global_Timetable.inc';
        ?>
    </div>
</main>
</body>
</html>