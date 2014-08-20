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
class template {
	var $titolo;
	var $filename;
	var $alta;
	var $bassa;
	var $pagina;
	var $contenuto;
	var $setta_alta;
	var $setta_bassa;
	Function setta_titolo($title) {
		$this->titolo = $title;
	}
	Function setta_filename($filename) {
		$this->filename = $filename;
	}
	Function setta_alta() {
		if (!isset($this->setta_alta)) {
			$this->setta_alta = 1;
		}
		//$dir = dirname($_SERVER['PHP_SELF']);
		$testata = implode('', file('testata.php'));
		$keywords = '  <meta name="keywords" content="metodo del simplesso, 
programmazione lineare, forma standard, forma canonica, tableau, metodo delle 
due fasi, programmazione lineare intera, medodo dei piani di taglio, variabili 
slack, variabili surplus, variabili ausiliarie, variabili artificiali, forma di 
inamissibilità, regione di ammissibilità, Gionata Massi">
';
		$style = '  <link rel="StyleSheet" href="stile.css" type="text/css" media="screen">';
		$this->alta = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>Tesina di RICERCA OPERATIVA - ' . $this->titolo . ' - Gionata 
Massi</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name=author content="Gionata Massi">
' . $keywords . $style . '
</head>

<body>

' . $testata. '
  <!-- TAGLIA 1 -->
  <hr align="left" width="100%">
  <br>
  <br>
  <!-- FINE TESTATA -->
';
	}
	Function setta_bassa() {
		if (!isset($this->setta_bassa)) {
			$this->setta_bassa = 1;
		}
		$this->bassa = '
  <!-- INIZIO PIEDE -->
  <br>
  <br>
  <hr align="left" width="100%">
  <!-- TAGLIA 2 -->

  <table border="0" width="100%" cellspacing="0" cellpadding="0"
  summary="link a fondo pagina">
    <thead>
      <tr>
        <th width="25%" align="left" valign="top"><a href=
        "index.php"><img src="images/top.png" alt="[HOME]  "
        height="32" width="32" border="0"> Tornare alla pagina
        principale.</a></th>

        <th width="25%" align="center" valign="top"><a href=
        "immissione_dati_0.php"><img src="images/up.png" alt=
        "[NEW]  " height="32" width="32" border="0"> Inserire un nuovo
        problema.</a></th>

        <th width="25%" align="right" valign="top"><a href=
        "show_src.php?script=' . $this->filename . '" target="sorgenti">
        <img src="images/src.png" alt="[SRC]  " height="32" width="32" border="0"> 
        Vedi il codice sorgente del file.</a></th>
        
        <th width="25%" align="right" valign="top"><a href=
        "info.php" target="info"><img src="images/doc.png"
        alt="[INFO]  " height="32" width="32" border="0"> Andare alla
        documentazione.</a></th>
      </tr>
    </thead>
  </table>
  <hr width="100%">
  <br>
  <br>

  <table width="100%" summary=
  "divide in due lo schermo per un migliore output grafico e la massima 
separazione fra nome e indirizzo email">  <tbody>
      <tr>
        <td width="50%" align="left">
          <address>
            Creato da <strong>Gionata Massi</strong><br>
            Copyright (c) 2003
          </address>
        </td>

        <td width="50%" align="right">
          <address>
            Email: <a href=
            "mailto:g.massi@univpm.it">g.massi@univpm.it</a><br>
          </address>
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>';
	}
	Function setta_contenuto($content) {
		$this->contenuto = $content;
	}
	Function mostra_pagina() {
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		if (!isset($this->setta_alta)) {
			$this->setta_alta();
		}
		if (!isset($this->setta_bassa)) {
			$this->setta_bassa();
		}
		$this->pagina = $this->alta . '<h2>' . "$this->titolo" . '</h2>' . $this->contenuto . $this->bassa;
		return ($this->pagina);
	}
}
?>
