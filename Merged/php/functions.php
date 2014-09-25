<?php
class functions
{

	public function start_session() {
		if (session_status() == PHP_SESSION_NONE) {
	    session_start();
		}
	}
}