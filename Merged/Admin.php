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
    <link href="CSS/Admin.CSS" rel="stylesheet" type="text/css">
</head>
<body>
<main>
    <?php include 'Include/Header.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>
                    <p> > Admin</p>";
    include 'Include/LocationSeparator.inc'; ?>
    <?php include 'Include/SideBar.inc'; ?>
    <?php include 'Include/Admin.inc'; ?>

</main>
</body>
</html>