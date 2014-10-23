<?php

require 'PHP/uac.php';
require 'PHP/SqlObject.php';
$UserAccessControl = new UserAccessControl();

if(isset($_POST['password'])){
    $sql = 'UPDATE STIMulate.staff SET staff_password = MD5(:password), passReset = 0 WHERE staff_id = :staff_id';
    $sqlObject = new \PHP\SqlObject($sql, array($_POST['password'], $_SESSION['user_id']));
    $sqlObject->Execute();
    $_SESSION['requires_reset'] = false;
}
if (!$UserAccessControl->isUserLoggedIn()) {
    header("Location: Global_Gateway.php");
} else {
    if(!$_SESSION['requires_reset']){
        header("Location: Volunteer_Shifts.php");
    }
}

$UserAccessControl->checkTimeout();
?>

<!DOCTYPE html>

<html>
<head>
    <?php include 'Include/Global_Head.inc'; ?>
    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/Admin_Login_Reset.css" rel="stylesheet" type="text/css">


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

    ?>

    <div id = "passwordWrapper">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Change your password</h2>
            </div>
        <form method="POST" action="" id = "passwordform">
            <label>Password:</label>
            <input style="display:none" type="text" name="fakeusernameremembered"/>
            <input style="display:none" type="password" name="fakepasswordremembered"/>
            <input type="password" name = "password" placeholder="Password" autocomplete="off" REQUIRED>
            <label>Confirm Password:</label>
            <input type="password" name = "confirmPassword" placeholder="Confirm Password" autocomplete="off" REQUIRED>
            <input type="submit" name = "Submit">
        </form>
    </div>

</main>
</body>
</html>
