<?php
/**
 * Created by PhpStorm.
 * User: Hayden
 * Date: 6/10/14
 * Time: 12:11 PM
 */

?>

<?php include 'PHP/functions.php'; ?>
<?php require 'PHP/uac.php'; ?>
<?php
$UserAccessControl = new UserAccessControl();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!$UserAccessControl->isUserLoggedIn()) {
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
    <link href="CSS/Absentie.CSS" rel="stylesheet" type="text/css">
    <script src = "JS/Absentie.js"></script>
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
    <?php include 'Include/Volunteer_Record_Absent.inc'; ?>
</main>
</body>
</html>
