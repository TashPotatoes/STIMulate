<?php
    require_once '/php/databaseAPI.php';
    require_once '/php/SqlObject.php';
    require_once '/temp/login.php';
	$login = new Login();

	if ($login->isUserLoggedIn() == true) {
	    echo "legged in";

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
    <input id="login_input_username" class="login_input" type="text" name="user_name" required />

    <label for="login_input_password">Password</label>
    <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required />

    <input type="submit"  name="login" value="Log in" />

</form>

