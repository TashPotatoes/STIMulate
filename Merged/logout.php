<?php
    require_once '/php/databaseAPI.php';
    require_once '/php/SqlObject.php';
    require_once '/php/uac.php';
	$UserAccessControl = new UserAccessControl();

	$UserAccessControl->doLogout();
	header("Location: facilitator.php");
?>

<!-- login form box -->


