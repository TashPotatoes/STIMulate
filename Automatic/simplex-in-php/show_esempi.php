<?php
/*
 * Copyright (C) 2003, 2004, 2005, 2006 Gionata Massi
 *
 * This file is part of Simplex-in-PHP.
 *
 *  Simplex-in-PHP is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  Simplex-in-PHP is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Foobar; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
*/
$head = '<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en\" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Esempio: ' . $_GET["page"] . '</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body>';
$tail = '</body>
</html>';
$XDEBUG_SESSION_START="testID";
$page = $_GET["page"];
$path = dirname($_SERVER["SCRIPT_FILENAME"]);
$path.= "/esempi";

chdir($path);

$page = basename($page);
$htmlpage = $page . ".html";
$errore = - 1;

if (file_exists($page)) if ((!file_exists($htmlpage)) or filemtime($page) != filemtime($htmlpage)) {
	$host = $_SERVER["HTTP_HOST"];
	//$url = "/~gim/ro/simplesso.php";
	
	$url=dirname($_SERVER["SCRIPT_NAME"]) . '/simplesso.php';
	$errore = 1;
	$retutn_value = 0;
	$html = array();

	// echo "exec(\"$path/prova_simplesso.exe $host $url $page\", $html, $return_value); <br />\n";
	exec("$path/prova_simplesso $host $url $page", $html, $return_value);
	if ($return_value == 0) touch($htmlpage, filemtime($page));
	else $errore = 1;
}
switch ($errore) {
	case -1:
		if (!file_exists($htmlpage)) $html = $head . "<H1>ERRORE: parametri errati o file $page inesistente.</H1>" . $tail;
		else $html = implode('', file($htmlpage));
		break;

	case 0:
		$html = implode('', file($htmlpage));
		break;

	case 1:
		$html = $head . "<H1>ERRORE: 'prova_simplesso` non pu&ograve; essere eseguito o non riesce a terminare regolarmente.</H1>" . $tail;
		break;
	}
	print $html;
?>

