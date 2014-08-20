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
$content = '
   <script language="javascript" type="text/javascript">

function checkData () {
    if (document.form0.numVariables.value == "" ||
        document.form0.numConstraints.value == "") {
			alert("Devi riempire interamente il modulo prima di procedere.")
			return false
	}
	if (! isFinite(document.form0.numVariables.value) ||
		document.form0.numVariables.value < 0 ||
		document.form0.numVariables.value > 10) {
			alert("Introduci come  numero delle variabili di decisione valori compresi fra 0 e 10 . ")
			return false
	}
	if (! isFinite(document.form0.numVariables.value) ||
		document.form0.numConstraints.value < 0 ||
		document.form0.numConstraints.value > 10) {
			alert("Introduci come  numero di vincoli valori compresi fra 0 e 10. ")
			return false
	}
	document.form0.submit()
}
		
	</script>

      <form name="form0" method="post" action="immissione_dati_1.php">
        <p>Il problema &egrave; di <input type="radio" name="minmax"
 value="min" checked><strong>minimo</strong> <input type="radio" name="minmax"
 value="max"><strong>massimo</strong></p>
        <p>Numero delle variabili di decisione: <input type="text"
 name="numVariables" size="3" maxlength="3"></p>
        <p>Numero dei vincoli: <input type="text" name="numConstraints"
 size="3" maxlength="3"></p>
        <p>Tutte le variabili sono intese non negative. Spunta la
casella se devono essere <input type="checkbox" name="intera"
 value="true">INTERE.</p>
        <table border="0" summary="invio form">
          <thead>
            <tr>
              <th><input type="button" value=" Procedi " 
onClick="checkData()"></th>
              <th><input type="reset" value=" Cancella "></th>
            </tr>
          </thead>
        </table>
      </form>

	';
// visualizzazione
$title = 'Tipo e dimensioni del problema';
$pagina = new template;
$pagina->setta_titolo($title);
$pagina->setta_filename(basename($_SERVER["SCRIPT_NAME"]));
$pagina->setta_contenuto($content);
print ($pagina->mostra_pagina());
?>
