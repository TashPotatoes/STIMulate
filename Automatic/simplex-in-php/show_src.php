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
?>

<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Sorgente di "<?php
echo $_GET["script"] ?>"</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body>

<?php
$script = $_GET["script"];
if (!$script) {
	echo "<BR><B>ERROR: Script Name needed</B><BR>";
} else {
	if (preg_match("/(\.php|\.inc|\.c|\.cpp|\.h|\.hpp)$/i", $script)) {
		echo "<h1 align=\"center\">$script</h1>\n";
		// sul server sono disattivate le segunti funzioni
		// highlight_file($script) e show_source ($script);
		if (file_exists($script) && is_readable($script)) {
			$html_script = implode('', file($script));
			highlight_string($html_script);
		}
	} else {
		echo "<H1>ERROR: Only PHP or include script names are allowed</H1>";
	}
}
?>

</body>
</html>
