<?php
class functions
{
	// Start session to retreive / store sessional variables to user browser
	public function start_session() {
		if (session_status() == PHP_SESSION_NONE) {
	    session_start();
		}
	}
}