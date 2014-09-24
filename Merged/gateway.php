<?php
    require_once '/php/databaseAPI.php';
    require_once '/php/SqlObject.php';
    require_once '/php/uac.php';
	$login = new Login();

	if ($login->isUserLoggedIn() == true) {


	} else {
		if (isset($login)) {
		    if ($login->errors) {
		        foreach ($login->errors as $error) {
		            echo $error;
		        }
		    }
		    if ($login->messages) {
		        foreach ($login->messages as $message) {
		            echo $message;
		        }
		    }
		}
	}
?>

<!-- login form box -->
<form method="post" action="gateway.php" name="loginform">

    <label for="login_input_username">Username</label>
    <input id="login_input_username" class="login_input" type="text" name="login_input_username" required />

    <label for="login_input_password">Password</label>
    <input id="login_input_password" class="login_input" type="password" name="login_input_password" autocomplete="off" required />

    <input type="submit"  name="login" value="Log in" />

</form>

