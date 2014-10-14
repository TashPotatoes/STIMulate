<?php
/**
 * Created by PhpStorm.
 * User: Hayden
 * Date: 6/10/14
 * Time: 6:06 PM
 */

?>

<?php require 'PHP/uac.php'; ?>
<?php $UserAccessControl = new UserAccessControl();
if (!$UserAccessControl->isUserLoggedIn() == true) {
    header("Location: gateway.php");
}
$UserAccessControl->checkTimeout();
?>

<!DOCTYPE html>

<html>
<head>
    <?php include 'Include/GlobalHead.inc'; ?>
    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/TimeTableFilter.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/ManageAdminControls.CSS" rel="stylesheet" type="text/css">
    <script src = "JS/CsvControls.js"></script>
    <script src = "JS/ManageTableControls.js"></script>
</head>
<body>
<main>
    <?php include 'Include/Header.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>";
    include 'Include/LocationSeparator.inc'; ?>
    <?php include 'Include/SideBar.inc'; ?>
    <?php include 'Include/ManageStudentCSV.inc'; ?>
</main>
</body>
</html>
