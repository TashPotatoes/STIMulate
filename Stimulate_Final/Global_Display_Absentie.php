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
    <link href="CSS/Dashboard.CSS" rel="stylesheet" type="text/css">
</head>
<body>
<main>
    <?php include 'Include/Global_Page_Head.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>";
    include 'Include/Global_Breadcrumb.inc'; ?>
    <?php include 'Include/Global_Sidebar.inc'; ?>
    <?php include 'Include/Global_Display_Absent.inc'; ?>
</main>
</body>
</html>
