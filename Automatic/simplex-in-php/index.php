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
$title = "Implementazione del METODO DEL SIMPLESSO in PHP";
$content = '
	<ul>
	 <li><a href="immissione_dati_0.php">Esegui la ricerca dell\'ottimo.</a></li>
	 <li><a href="info.php">Come funziona il programma.</a></li>
	 <li><a href="info.php#scelte">Scelte implementative</a></li>
	</ul>
	';
// visualizzazione
$pagina = new template;
$pagina->setta_titolo($title);
$pagina->setta_filename(basename($_SERVER["SCRIPT_NAME"]));
$pagina->setta_contenuto($content);
print ($pagina->mostra_pagina());
?>
