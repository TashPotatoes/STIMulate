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
require 'template.php';
// Rinominazione variabili.
foreach ($_POST as $var => $value) {
	$$var = $value;
}
date_default_timezone_set('UTC');
// Creazione form immisione dati.
$content = '
	<script language="javascript" type="text/javascript">
		function looksLikeANumber(theString) {
			// returns true if theString looks like it can be evaluated
			var result = true;
			var length = theString.length;
			if (length == 0) return (true); // valutato da simplesso.php come 0
			var x = ""
			var y = "1234567890-+*. /"
			var yLength = y.length;
			for (var i = 0; i <= length; i++) { 
				x = theString.charAt(i);
				result = false;
				for (var j = 0; j <= yLength; j++) {
					if (x == y.charAt(j)) {result = true; break}
				} // j
				if (result == false) return(false);
			} // i
			return(result);
		} // looks like a number

		function checkData(dataForm) {
			for (var i = 0; i < dataForm.length-2; i++) { 
				name = dataForm.elements[i].name;
				if (name.substr(0,3) == "lge" || name == "minmax" || name == "name" || name == "intera" || name == "XDEBUG_SESSION_START") {}
				else {
					if (! looksLikeANumber (dataForm.elements[i].value)) {
						alert (name + " non e\' un numero!\n")
						return false
					}
				}
			} // check data
			dataForm.submit()
		}
	</script>
	
	<form name="form1" method="post" action="simplesso.php">
	<input type="hidden" name="minmax" value="' . $minmax . '">
	<input type="hidden" name="numVariables" value="' . $numVariables . '">
	<input type="hidden" name="numConstraints" value="' . $numConstraints . '">
 	<input type="hidden" name="name" value="tmp' . date("siH") . '">' .
 	'<input type="hidden" name="XDEBUG_SESSION_START" value="testID">' .
 	'<input type="hidden" name="XDEBUG_PROFILE" value="">' .
 	"\n";
// per vedere i dati codificati inviati dal browser sostituire la riga di
// intestazione del form con qualcosa tipo:
// <form name="form1" method="post" action="http://athlon:5555/simplesso.php">
// ed eseguire un demone sulla porta 5555 che stampi il suo input
if (isset($intera) && !strcmp($intera, "true")) $content.= '<input type="hidden" name="intera" value="true">';
else $content.= '<input type="hidden" name="intera" value="false">';
$content.= '
	<strong>' . $minmax . ' z = ';
// 1ma riga: c^t x + ...
for ($j = 0; $j < $numVariables; $j++) {
	$content.= sprintf("<input type=\"text\" name=\"c[%d]\" size=\"5\" 
maxlength=\"5\"> x<sub>%d</sub> +\n", $j + 1, $j + 1);
}
// ... d.
$content.= ' <input type="text" name="d" size="5" maxlength="5"><br><br>
	Soggetto a<br><br>
	';
// Crea le righe dei vincoli.
for ($i = 0; $i < $numConstraints; $i++) {
	// Numero del vincolo.
	$content.= $i + 1 . ') ';
	// Un caso a parte per l'immissione di x1.
	$content.= sprintf("<input type=\"text\" name=\"a[%d][1]\" size=\"5\" 
maxlength=\"5\"> x<sub>1</sub>\n", $i + 1); // Le altre $numVariables variabili.
	for ($j = 1; $j < $numVariables; $j++) {
		$content.= sprintf("+ <input type=\"text\" name=\"a[%d][%d]\" size=\"5\" 
maxlength=\"5\"> x<sub>%d</sub>\n", $i + 1, $j + 1, $j + 1);
	}
	$content.= sprintf("<select 
name=\"lge[%d]\"><option>=&lt;<option>&gt;=<option>=</select> <input 
type=\"text\" name=\"b[%d]\" size=\"5\" maxlength=\"5\"><br>\n", $i + 1, $i + 1);
}
// non negativita'
$content.= '
	&nbsp; &nbsp; x<sub>i</sub> &gt;= 0';
// ed eventuale interezza
if (isset($intera) && !strcmp($intera, "true")) $content.= ' e INTERI';
$content.= ' &nbsp; per i =1,...,' . $numVariables;
// I bottoni per inviare il modulo.
$content.= '</strong><br>
        <table border="0" summary="invio form">
          <thead>
            <tr>
              <th><input type="button" value=" Procedi " 
onClick="checkData(this.form)"></th>
              <th><input type="reset" 
value=" Cancella "></th>
            </tr>
          </thead>
        </table>
	
	';
// Le istruzioni per visualizzare la pagina.
$title = 'Immissione dati';
$pagina = new template;
$pagina->setta_titolo($title);
$pagina->setta_filename(basename($_SERVER["SCRIPT_NAME"]));
$pagina->setta_contenuto($content);
print ($pagina->mostra_pagina());
?>
