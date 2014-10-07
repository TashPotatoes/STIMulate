<?php
    require_once '/php/databaseAPI.php';
    require_once '/php/SqlObject.php';
    require '/php/uac.php';
	$UserAccessControl = new UserAccessControl();
	if ($UserAccessControl->isUserLoggedIn() == true) {
        header("Location: dashboard.php");
	} else {
        $errors = 1;
	}
    $UserAccessControl->checkTimeout();
?>

<!DOCTYPE html>

<html>
    <head>
        <?php include 'Include/GlobalHead.inc'; ?>
        <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
        <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
        <link href="CSS/Gateway.CSS" rel="stylesheet" type="text/css">
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
<div>
<?php
if($errors) {
    if (isset($UserAccessControl)) {
        if ($UserAccessControl->errors) {
            foreach ($UserAccessControl->errors as $error) {
                echo $error;
            }
        }
        if ($UserAccessControl->messages) {
            foreach ($UserAccessControl->messages as $message) {
                echo $message;
            }
        }
    }
}
?>
</div>
<form method="post" action="gateway.php" name="loginform" id = "loginForm">

    <label for="login_input_username">Username</label>
    <input id="login_input_username" class="login_input" type="text" name="login_input_username" placeholder="Username" required />

    <label for="login_input_password">Password</label>
    <input id="login_input_password" class="login_input" type="password" name="login_input_password" autocomplete="off" placeholder="Password" required />

    <input type="submit"  name="login" value="Log in" />

</form>

        </main>
    </body>
</html>
