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

	Function mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera)
	/* mostra il problema come e' stato immesso */ // Copiata da matrice.display_equations()
	{
		$content = '
		<strong>' . $minmax . ' z = ';

		// 1ma riga: c^t x + ...
		if (! isset($c[1]))
			echo "Errore: non esiste c[1]\n";
		elseif ($c[1]->value() == 1) {
			$content .= sprintf(" x<sub>1</sub>");
		} elseif ($c[1]->num() == 0) {
			// $content .= sprintf("&nbsp; &nbsp;"); 
		} elseif ($c[1]->value() == -1) {
			$content .= sprintf(" - x<sub>1</sub>");
		} else {
			$content .= sprintf(" %s x<sub>1</sub>", $c[1]->fractoa());
		}			
		for ($j=2; $j<$numVariables+1; $j++) {
			if (! isset($c[$j])) { // == 0
					// $content .= sprintf("&nbsp; &nbsp;");
			} elseif ($c[$j]->num() >= 0) {
				if ($c[$j]->value() == 1) { // == 1
					$content .= sprintf(" + x<sub>%d</sub>", $j);
				} elseif ($c[$j]->num() == 0) { // == 0
					// $content .= sprintf("&nbsp; &nbsp;");
				} else { // > 0 && != 1
					$content .= sprintf(" + %s x<sub>%d</sub>", $c[$j]->fractoa(), $j);
				}
			} elseif ($c[$j]->value() == -1) { // = -1
					$content .= sprintf(" - x<sub>%d</sub>", $j);
			} else {  // < 0 != -1
				$c1 = new razionale (-$c[$j]->num(), $c[$j]->den());
				if ($c[$j]->value() == -1) { // == -1
					$content .= sprintf(" - x<sub>%d</sub>", $j);
				} else {
					$content .= sprintf(" - %s x<sub>%d</sub>", $c1->fractoa(), $j);
				}
				unset ($c1);
			}
		}
		// ... + (-d)
		if (! isset($d))
			; // d == 0 => non visualizzare niente
		elseif ($d->num() > 0) { 
			$content .= sprintf(" + %s ", $d->fractoa());
		} elseif ($d->num() < 0) {
			$d1 = new razionale (-$d->num(), $d->den());
			$content .= sprintf(" - %s ", $d1->fractoa());
			unset ($d1);
		} else
			; // d == 0 => non visualizzare niente

		$content .= '<br><br>
		Soggetto a<br><br>
		';
		// righe dei vincoli
		for ($i=1; $i<$numConstraints+1; $i++) {
			// riga del vincolo
			$content .= $i . ') ';
			// variabile 1ma colonna
			if (! isset($a[$i][1]))
				;
			elseif ($a[$i][1]->value() == 1) {
				$content .= sprintf(" x<sub>1</sub>");
			} elseif ($a[$i][1]->num() == 0) {
				// $content .= sprintf("&nbsp; &nbsp;");
			} elseif ($a[$i][1]->value() == -1) {
				$content .= sprintf(" - x<sub>1</sub>");
			} else {
				$content .= sprintf(" %s x<sub>1</sub>", $a[$i][1]->fractoa());
			}
			// le altre colonne
			for ($j=2; $j<$numVariables+1; $j++) {
				if (! isset($a[$i][$j]))
					;
				elseif ($a[$i][$j]->num() >= 0) { // >= 0
					if ($a[$i][$j]->value() == 1) {
						$content .= sprintf(" + x<sub>%d</sub>", $j);
					} elseif ($a[$i][$j]->num() == 0 ) {
						// $content .= sprintf("&nbsp; &nbsp;");
					} else {
						$content .= sprintf(" + %s x<sub>%d</sub>", $a[$i][$j]->fractoa(), $j);
					}
				} elseif ($a[$i][$j]->value() == -1) { // == -1
						$content .= sprintf(" - x<sub>%d</sub>", $j);
					} else {
					$a1 = new razionale (-$a[$i][$j]->num(), $a[$i][$j]->den());
					$content .= sprintf(" - %s x<sub>%d</sub>", $a1->fractoa(), $j);
					unset ($a1);
				}
			}
			// risorse
			$content .= sprintf(" %s %s <br>", htmlentities($lge[$i]), $b[$i]->fractoa());
		}
			// non negativita'
		$content .= '
		&nbsp; &nbsp; &nbsp; &nbsp; x<sub>i</sub> &gt;= 0';
		// ed eventuale interezza
		if (! strcmp ($intera,"true"))
			$content .= ' e INTERI';
		$content .= " &nbsp; &nbsp; per i = 1,...,$numVariables</strong><br><br>\n\n";

		return $content;
	}

?>
