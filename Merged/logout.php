<?php
    require_once '/php/uac.php';
	$UserAccessControl = new UserAccessControl();

	$UserAccessControl->doLogout();
	header("Location: gateway.php?ref=logout");
?>
