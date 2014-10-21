<?php
    require_once 'uac.php';
	$UserAccessControl = new UserAccessControl();

	$UserAccessControl->doLogout();
	header("Location: ../Global_Gateway.php?ref=logout");
?>
