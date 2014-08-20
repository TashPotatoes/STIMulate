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
/*

Le variabili piu' importanti usate nel programma.
-- Per l'uso negli algoritmi vedere 'matrice.php'. --

//     $minmax          Il problema e' di max o di min? Valori: {"min", "max"}
//     $numVariables    Il numero di variabili di decisione del problema. Valori in N
//     $numConstraints  Il numero di vincoli del problema. Valori in N
//     $c[]             I coefficienti di costo della funzione obiettivo. Valori in R^n (Q^n)
//     $d               Il termine noto della funzione obiettivo. Valore in R (Q)
//     $a[][]           La matrice dei coefficienti tecnologici (in origine),
//                        successivamente l'intero tableau. Valori in R (Q)
//     $lge[]           Il verso delle disequazioni. Valori in {"=<",">=","="}
//     $b[]             Il vettore delle risorse. Valori in R^m (Q^m)
//    $intera             Il problema e' di programmazione lineare intera
//
// $base[]           L'insieme degli indici delle variabili in base
// $rho[]             Forma di inammissibilita'
// $base_df[]        L'insieme degli indici di base nella fase 1 del metodo delle 2 fasi
//
// $numArtificials   Il numero di variabili artificiali. Valori in N
// $numAux           Il numero di variabili di ausiliarie. Valori in N
// $result             Valore di ritorno della funzione matrice::simplesso()

*/
require 'template.php';
require 'matrice.php';
require 'util.php';
require 'razionale.php';
require 'immagini.php';

function scrivi_pagina($content) /* stampa a video l''output del programma, secondo il template */ {
	$title = 'Analisi';
	$pagina = new template;
	$pagina->setta_titolo($title);
	$pagina->setta_filename(basename($_SERVER["SCRIPT_NAME"]));
	$pagina->setta_contenuto($content);
	print ($pagina->mostra_pagina());
}
function init_variables(&$minmax, &$numVariables, &$numConstraints, &$_a, &$_b, &$_c, &$_d, &$lge, &$intera, &$cambia_segno, &$grafico, &$image, &$name) /* Legge i dati che gli sono stati inviati e crea le variabili corrispondenti */ {
	// rinominazione delle variabili
	foreach ($_POST as $var => $value) {
		$$var = $value;
		// echo "$var = $value<br />\n";
	} //$_POST as $var => $value
	// la soluzione va cambiata di segno?
	if (!strcmp($minmax, "max")) $cambia_segno = true;
	else $cambia_segno = false;
	// iniziamo a generare _a
	for ($j = 1; $j < $numVariables + 1; $j++) {
		for ($i = 1; $i < $numConstraints + 1; $i++) {
			$_a[$i][$j] = new razionale;
			if (isset($a[$i][$j]))
				$_a[$i][$j]->scanfrac($a[$i][$j]);
			
		} //$i = 1; $i < $numConstraints + 1; $i++
		
	} //$j = 1; $j < $numVariables + 1; $j++
	// _b
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		$_b[$i] = new razionale;
		if (isset($b[$i]))
			$_b[$i]->scanfrac($b[$i]);
		
	} //$i = 1; $i < $numConstraints + 1; $i++
	// _c
	for ($j = 1; $j < $numVariables + 1; $j++) {
		$_c[$j] = new razionale;
		if (isset($c[$j]))
			$_c[$j]->scanfrac($c[$j]);
		
	} //$j = 1; $j < $numVariables + 1; $j++
	// _d
	$_d = new razionale;
	if (isset($d))
		$_d->scanfrac($d);

	if ($numVariables == 2) {
		$grafico = true;
		$name = 'images/tmp/' . $name;
		$image = new grafico($_a, $_b, $_c, $lge, !$cambia_segno, $name);
	} //$numVariables == 2
	if (!strcmp($_SERVER['SERVER_ADDR'], "127.0.0.1")) {
		$prova_simplesso_file  = "### xxx\n#\n";
		$prova_simplesso_file .= "minmax = $minmax\n";
		$prova_simplesso_file .= "numVariables = $numVariables\n";
		$prova_simplesso_file .= "numConstraints = $numConstraints\n";
		$prova_simplesso_file .= "intera = $intera\n";
		$prova_simplesso_file .= "c = [ ";
		for ($jj = 1; $jj <= $numVariables; $jj++)
			$prova_simplesso_file .= $_c[$jj]->fractoa() . " ";
		$prova_simplesso_file .= "]\n";
		$prova_simplesso_file .= "d = " . $_d->fractoa() . "\n";
		$prova_simplesso_file .= "a = [ ";
		for ($ii = 1; $ii <= $numConstraints; $ii++) {
			for ($jj = 1; $jj <= $numVariables; $jj++)
				$prova_simplesso_file .= $_a[$ii][$jj]->fractoa() . " ";
			if ($ii != $numConstraints)
				$prova_simplesso_file .= ";\n\t";
		}
		$prova_simplesso_file .= "]\n";
		$prova_simplesso_file .= "b = [ ";
		for ($ii = 1; $ii <= $numConstraints; $ii++)
			$prova_simplesso_file .= $_b[$ii]->fractoa() ." ";
		$prova_simplesso_file .= "]\n";
		$prova_simplesso_file .= "lge = [ ";
		for ($ii = 1; $ii <= $numConstraints; $ii++)
			$prova_simplesso_file .= "$lge[$ii] ";
		$prova_simplesso_file .= "]\n";
		$fp = fopen('esempi/tmp.txt', 'w');
		fwrite($fp, $prova_simplesso_file);
		fclose($fp);
	}
	unset($a);
	unset($b);
	unset($c);
	unset($d);
	
} // END init_variables()
function riduci_standard(&$minmax, &$numVariables, $numConstraints, &$a, &$b, &$c, &$d, &$lge, &$intera, &$cambia_segno, &$base) {
	$tmp = "<p>";
	// Il problema e' di minimo o di massimo?
	if (strcmp($minmax, "min")) {
		// traformiamo il problema di max in min
		for ($j = 1; $j < $numVariables + 1; $j++) {
			$c[$j]->negatefrac();
		} //$j = 1; $j < $numVariables + 1; $j++
		$d->negatefrac();
		$minmax = "min";
		$tmp = "&Egrave; stato cambiato il problema da massimo a minimo cambiando il segno di <b>z</b>.<br>\n";
	} //strcmp( $minmax, "min" )
	// aggiungiamo variabili slack o variabili surplus
	// nella colonna $numVariables + $numAux;
	// prima va aggiornato $numAux e successivamente $a
	$numAux = 0;
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		if (!strcmp($lge[$i], "=<")) {
			// slack
			$numAux++;
			$j = $numVariables + $numAux;
			$a[$i][$j] = new razionale(1, 1);
			$lge[$i] = "=";
			$tmp.= "Introdotta la variabile <em>slack</em> x<sub>$j</sub> in riga $i.<br>\n";
			// se b >= 0 -> e' sicuramente in base $base[]=(variabile => riga)
			if ($b[$i]->value() >= 0) $base[] = array($i => $j);
			/* non sarebbe questo il luogo per tale operazione ma risparmiamo
			                                             una inutile ricerca nella riduzione in forma canonica per sapere
			                                             che x[$j] e' in base */
		} //!strcmp( $lge[$i], "=<" )
		else if (!strcmp($lge[$i], ">=")) {
			// surplus
			$numAux++;
			$j = $numVariables + $numAux;
			$a[$i][$j] = new razionale(-1, 1);
			$lge[$i] = "=";
			$tmp.= "Introdotta la variabile <em>surplus</em> x<sub>$j</sub> in riga $i.<br>\n";
			if ($b[$i]->value() < 0) $base[] = array($i => $j);
		} //!strcmp( $lge[$i], ">=" )
		
	} //$i = 1; $i < $numConstraints + 1; $i++
	$numVariables+= $numAux;
	$tmp.= "</p>\n\n";

	return $tmp;
} // END riduci_standard()
function risorse_non_negative(&$a, &$b, &$lge, $numVariables, $numConstraints) {
	// portiamo i b[i] ad essere tutti non negativi
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		// che deve esere positivo (non negativo per soluzione degenere)
		if (isset($b[$i]) and $b[$i]->num() < 0) { // risorsa negativa
			$cambia_segno_vincolo[] = $i;
			// moltiplica la riga per -1
			$b[$i]->negatefrac();
			for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $a[$i][$j]->negatefrac();
			// e cambia verso alla disequaione
			if (!strcmp($lge[$i], "=<")) $lge[$i] = ">=";
			else if (!strcmp($lge[$i], ">=")) $lge[$i] = "=<";
		} //isset( $b[$i] ) and $b[$i]->num() < 0
		
	} //$i = 1; $i < $numConstraints + 1; $i++
	if (isset($cambia_segno_vincolo) && count($cambia_segno_vincolo)) {
		// alcune righe sono state moltiplicate per -1
		for ($i = 0; $i < count($cambia_segno_vincolo); $i++) $tmp = "Il vincolo numero " . $cambia_segno_vincolo[$i] . " &egrave; moltiplicato per -1 al fine di ottentere la risorsa positiva.<br>\n";
	} //isset( $cambia_segno_vincolo ) && count( $cambia_segno_vincolo )
	else $tmp = "";
	return $tmp;
} // END risorse_non_negative()
function di_base($a, $c, $numConstraints, $numVariables, $i, $j) /* restituisce true se la variabile della colonna $j e' di base */ {
	if (isset($c[$j]) and $c[$j]->num() != 0) return false;
	// per ogni riga di $a
	for ($k = 1; $k < $numConstraints + 1; $k++)
	// diversa da quella per cui $a[$i][$j] dovra' essere 1
	if ($k != $i)
	// se $a[$k][$j] != 0
	if (isset($a[$k][$j]) and $a[$k][$j]->num() != 0)
	// allora non e' di base
	return false;
	// l'ultima riga viene eseguita solo se le condizioni sono rispettate
	return true;
}
// END di_base()
function cerca_base($a, $c, $numConstraints, $numVariables, $i, &$div) /* verifica se la variabile x[$j] puo' entrare in base ed eventualmente
 compie le operazione per portarla in base */ {
	$div = new razionale;
	// per ogni variablile in $a
	for ($j = 1; $j < $numVariables + 1; $j++) {
		// se e' presente nell'equazione del vincolo $i con costante > 0
		if (!isset($a[$i][$j])) continue;
		$div = $a[$i][$j];
		if ($div->num() > 0) {
			// e non e' presente nella funzione obiettivo e negli altri vincoli
			if (di_base($a, $c, $numConstraints, $numVariables, $i, $j)) {
				// se e' presente con costante 1 e' gia' in base
				// altrimenti andranno fatte le opportune divisioni
				// alla fine x[$j] sara' di base
				// andrebbe riportanto l'indice della riga
				return $j;
			} //di_base( $a, $c, $numConstraints, $numVariables, $i, $j )
			
		} //$div->num() > 0
		
	} //$j = 1; $j < $numVariables + 1; $j++
	// l'ultima riga viene eseguita solo se la ricerca fallisce
	unset($div);
	return 0;
} // END cerca_base()
function normalizza(&$a, &$b, $i, $j, $numVariables) {
	$div = new razionale;
	$div = $a[$i][$j];
	if ($div->value() == 1) return "";
	elseif ($div->num() > 0) {
		// dividi la riga per la costante $div
		$b[$i]->divfrac($b[$i], $div);
		for ($k = 0; $k < $numVariables + 1; $k++) {
			if (isset($a[$i][$k])) $a[$i][$k]->divfrac($a[$i][$k], $div);
		} //$k = 0; $k < $numVariables + 1; $k++
		$content.= "Il vincolo numero $i &egrave; diviso per " . $div->fractoa() . " al fine di non introdurre una variabile artificiale non necessaria.<br>\n";
	} //$div->num() > 0
	else $content.= "<font color=\"Red\" size=\"+4\">Errore: richiesta normalizzazione con pivot non positivo.<br></font>\n";
	return $content;
} // END normalizza()
function aggiungi_artificiali($i, $numArtificials, $numVariables, &$a, $b, &$rho, &$base) {
	$k = $numVariables + $numArtificials;
	$a[$i][$k] = new razionale(1, 1);
	for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) {
		$rho[$j]->subfrac($rho[$j], $a[$i][$j]);
	}
	$rho[0]->subfrac($rho[0], $b[$i]);
	$base[$i - 1] = $k;
	return "Introdotta la variabile artificiale x<sub>$k</sub> in riga $i.<br>\n";
} // END aggiungi_artificiali()
function gia_in_base(&$base, $i) {
	//TODO: check
	//for ($k = 0; $k < count($base); $k++)
	//foreach($base[$k] as $key  => $value)
	//if ($value == $i)
	//return true;
	if (isset($base[$i]) && $base[$i] != 0) return true;
	return false;
} // END gia_in_base()
function riduci_canonica(&$minmax, &$numVariables, &$numConstraints, &$numArtificials, &$a, &$b, &$c, &$d, &$lge, &$base, &$rho) {
	/* a: As=Im; b: cs=0; c: b>=0 */
	$base = array_fill(0, $numConstraints, 0);
	$tmp = "<p>";
	$numArtificials = 0;
	// Per prima cosa richiediamo che le risorse siano positive
	$tmp.= risorse_non_negative($a, $b, $lge, $numVariables, $numConstraints);
	// cerca $numConstraints variabili di base
	for ($i = 1; $i < $numConstraints + 1; $i++) if (!gia_in_base($base, $i - 1)) {
		// verifica se una delle variabili pu� entrare in base, in caso dividendola
		$div = 1;
		if ($j = cerca_base($a, $c, $numConstraints, $numVariables, $i, $div)) {
			// se necessario normalizza la riga
			if ($div->value() != 1) $tmp.= normalizza($a, $b, $i, $j, $numVariables);
			// aggiungi $j nell'insieme degli indici di base
			$base[$i - 1] = $j;
		} //$j = cerca_base( $a, $c, $numConstraints, $numVariables, $i, $div )
		else {
			if (!isset($rho)) for ($k = 0; $k < $numVariables + 1; $k++) $rho[$k] = new razionale(0, 1);
			$tmp.= aggiungi_artificiali($i, ++$numArtificials, $numVariables, $a, $b, $rho, $base);
			// TODO: Check!!!!
			$base[$i - 1] = $numVariables + $numArtificials;
		}
	} //!gia_in_base( $base, $i - 1 )
	$tmp.= "</p>\n\n";
	$numVariables+= $numArtificials;
	return $tmp;
} // END riduci_canonica()
function crea_tableau_simplesso($a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, &$Tableau) {
	$matrice[0][0] = new razionale(-$d->num(), $d->den());
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($c[$j])) $matrice[0][$j] = new razionale($c[$j]->num(), $c[$j]->den());
	for ($i = 0; $i < $numConstraints + 1; $i++) if (isset($b[$i])) $matrice[$i][0] = new razionale($b[$i]->num(), $b[$i]->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $matrice[$i][$j] = new razionale($a[$i][$j]->num(), $a[$i][$j]->den());
	$Tableau = new matrix($numConstraints + 1, $numVariables + 1, $matrice, $base, $cambia_segno);
} // END crea_tableau_simplesso()
function crea_tableau_fase_1($rho, $a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, &$Tableau) {
	// aggiungiamo la forma di inammissibilita'
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($rho[$j])) $matrice[0][$j] = new razionale($rho[$j]->num(), $rho[$j]->den());
	////$matrice[0][0] = new razionale(-$matrice[0][0]->num(), $matrice[0][0]->den());
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($c[$j])) $matrice[1][$j] = new razionale($c[$j]->num(), $c[$j]->den());
	if ($cambia_segno)
		$matrice[1][0] = new razionale(-$d->num(), $d->den());
	else
		$matrice[1][0] = new razionale($d->num(), $d->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) if (isset($b[$i])) $matrice[$i + 1][0] = new razionale($b[$i]->num(), $b[$i]->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $matrice[$i + 1][$j] = new razionale($a[$i][$j]->num(), $a[$i][$j]->den());
	// cambiare l'assegnamento agli indici di base
	for ($i = 0; $i < count($base); $i++) {
		//foreach ($base[$i] as $key => $value) {
			$base_df[$i] = $base[$i];
		//} //$base[$i] as $key => $value
		
	} //$i = 0; $i < count( $base ); $i++
	$Tableau = new matrix($numConstraints + 2, $numVariables + 1, $matrice, $base_df, $cambia_segno);
	if (isset($div))
		unset($div);
} // END crea_tableau_simplesso()
function fase_1(&$Tableau, &$content, $numArtificials) /*
 *
 * Eseguiamo la prima fase del metodo delle due fasi
 *
*/ {
	$content.= '<h2>Fase I</h2>';
	$uscita = false;
	$passo = 0;
	do {
		$content.= "<h4>Tableau al passo $passo:</h4>\n";
		$content.= '<table summary="mostra il tableau in un lato e nell\'altro il valore delle variabili" cellpadding="50%" cellspacing="50%">
 <tbody>
  <tr><td>';
		$content.= $Tableau->display_tableau();
		$content.= "</td>\n  <td>";
		$content.= $Tableau->display_status(1);
		$content.= "</td>\n  </tr>\n </tbody>\n</table>\n";
		// un passo di pivoting
		$result = $Tableau->simplesso($i, $j, 1);
		if ($result == 0) {
			$uscita = true;
			$content.= "&rho; &egrave; minimizzata.<br>\n";
		} //$result == 0
		else if ($result == - 1) {
			$uscita = true;
			$content.= "Caso impossibile: &rho; va a meno infinito. L\'algoritmo &egrave; implementato male.<br>\n";
			scrivi($content);
			if (isset($grafico)) {
				delete($grafico);
				exit(0);
			} //isset( $grafico )
			
		} //$result == -1
		else {
			$content.= "Soluzione non ammissibile. L'algoritmo continua ad iterare.<br>\n";
			$content.= "Pivot in riga <strong>r$i</strong> colonna <strong>x$j</strong>.<br>\n";
		}
		$passo++;
	}
	while ($uscita == false && $passo < 25);
	if ($passo == 25) $content.= "L'algoritmo termina perch&egrave; raggiunto il numero massimo di iterazioni previste.<br>\n";
	// ricordarsi che la soluzione in $Tableau->elemento(0,0) va interpretata col segno invertito
	// rho > 0
	$p = new razionale;
	$p = $Tableau->elemento(0, 0);
	if ($p->value() < 0) {
		$content.= 'La regione di ammisssibilit&agrave; &egrave; vuota.<p><font color="red" size="+2"><strong>Non esistono soluzioni.<br></strong></font></p>';
		scrivi_pagina($content);
		echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
		if (isset($grafico)) {
			delete($grafico);
			exit(0);
		} //isset( $grafico )
		exit(0);
		// rho < 0		
	} //$p->value() < 0
	else if ($p->value() > 0) {
		$content.= "Caso non previsto: &rho;, per definizione non negativa, &egrave; minore di zero. L'algoritmo &egrave; implementato male.<br>\n";
		scrivi_pagina($content);
		echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
		if (isset($grafico)) {
			delete($grafico);
			exit(0);
		} //isset( $grafico )
		exit(1);		
	} //$p->value() > 0
	else
	// forma inammissibilita' nulla
	// variabili artificiali fuori base
	if ($Tableau->fuori_base_artificiali($numArtificials)) $content.= "Tutte le variabili artificiali sono fuori base<br>\n";
	// variabili artificiali in base nulle
	else {
		$content.= "Alcune variabili artificiali sono rimaste in base. Occorrono altre operazioni di pivot.<br>\n";
		// crea l'array che ad ogni variabile x[$j] assegna
		// 0 se $j non e' indice di base, altrimenti la riga associata.
		for ($j = 1; $j < $Tableau->col; $j++) $arry[$j] = $Tableau->in_base($j);
		// per ogni variabile artificiale
		for ($j = $Tableau->col - $numArtificials; $j < $Tableau->col; $j++) {
			// se la variabile artificiale x[$j] e' in base
			if ($arry[$j] >= 0) {
				if ($Tableau->estrai_base_artificiale($j, $arry[$j], $k, $numArtificials)) {
					$content.= sprintf("Pivot in riga <strong>r%d</strong> colonna <strong>x%d</strong>.<br>", $arry[$j], $k);
					$content.= '<h4>Tableau al passo ' . $passo++ . ':</h4>';
					$content.= $Tableau->display_tableau();
					$content.= $Tableau->display_status(1);
				} //$Tableau->estrai_base_artificiale( $j, $arry[$j], &$k )
				else {
					$content.= '<strong>Una o pi&ugrave; variabili artificiali sono rimaste in base.</strong><br />';
					if ($p = 0)
						$content.='La soluzione è univocamente determinata.'; 
					else
					 $content.= 'Ci sono le equazioni ridondanti (linearmente dipendenti dalle altre).<br>Fin\'ora non ho implementato le routine atte allo scopo e l\'algoritmo termina.';
					// scrivi_pagina($content);
					if (isset($grafico)) {
						delete($grafico);
					} //isset( $grafico )
					
					exit(0);
				}
			} //$arry[$j] != 0
			
		} //$j = $Tableau->col - $numArtificials; $j < $Tableau->col; $j++
		
	}
} // END fase_1()
function fase_2(&$Tableau, &$content, $grafico, $name, &$image) {
	$content.= '<h2>Metodo del SIMPLESSO</h2>';
	$uscita = false;
	$passo = 0;
	do {
		$content.= '<h4>Tableau al passo ' . $passo . ':</h4>';
		$content.= '
    <table summary="mostra il tableau in un lato e nell\'altro il valore delle
    variabili" cellpadding="50%" cellspacing="50%">
     <tbody>
      <tr><td>';
		$content.= $Tableau->display_tableau();
		$content.= '</td>
      <td>';
		$content.= $Tableau->display_status(2);
		$content.= '</td>
      </tr>
     </tbody>
    </table>
    ';
		if (isset($grafico)) {
			if (!isset($x_1)) {
				$x_1 = $Tableau->sol[1]->value();
				$x_2 = $Tableau->sol[2]->value();
			} //!isset( $x_1 )
			$x_1old = $x_1;
			$x_2old = $x_2;
			$x_1 = $Tableau->sol[1]->value();
			$x_2 = $Tableau->sol[2]->value();
			$image->passo($x_1old, $x_2old, $x_1, $x_2, $passo, $name);
			$content.= "<center><img src=\"$name.png\" alt=\"[IMG]  Regione di ammissibilita'\" align=\"middle\"></center>";
		} //isset( $grafico )
		// un passo di pivoting
		$result = $Tableau->simplesso($i, $j, 2);
		if ($result == 0) {
			$uscita = true;
			if ($Tableau->unica()) {
				$content.= '<font color="red" size="+2">Soluzione <strong>ottima</strong>: &nbsp;&nbsp;&nbsp; </font>';
				$content.= $Tableau->soluzione_ottima();
			} //$Tableau->unica()
			else {
				$content.= '<strong>Questa &egrave; una delle possibili soluzioni ottime.</strong><br>';
				$verticeA = $Tableau->sol;
				$Tableau->altra_soluzione($i, $j, 2);
				$content.= sprintf("Pivot in riga <strong>r%d</strong> colonna <strong>x%d</strong>.<br>", $i, $j);
				$content.= '<h4>Tableau al passo ' . ++$passo . ':</h4>';
				$content.= '
    <table summary="mostra il tableau in un lato e nell\'altro il valore delle
    variabili" cellpadding="50%" cellspacing="50%">
     <tbody>
      <tr><td>';
				$content.= $Tableau->display_tableau();
				$content.= '</td>
      <td>';
				$content.= $Tableau->display_status(2);
				$content.= '</td>
      </tr>
     </tbody>
    </table>
    ';
				$verticeB = $Tableau->sol;
				$content.= $Tableau->soluzioni_ottime($verticeA, $verticeB);
			}
		} //$result == 0
		else if ($result == - 1) {
			$uscita = true;
			$content.= '<font color="red" size="+2">Soluzione <strong>illimitata</strong>.<br>L\'algoritmo termina.</font><br>';
		} //$result == -1
		else {
			$content.= 'Soluzione migliorabile. L\'algoritmo continua ad iterare.<br>';
			$content.= sprintf("Pivot in riga <strong>r%d</strong> colonna <strong>x%d</strong>.<br>", $i, $j);
		}
		$passo++;
	}
	while ($uscita == false && $passo < 25);
	if ($passo == 25) $content.= sprintf("L'algoritmo termina perch&egrave; raggiunto il numero massimo di iterazioni previste.<br>");
} // END fase_2()
function piani_di_taglio(&$Tableau, &$content) {
	$content.= '<h2>Risoluzione mediante metodo dei PIANI DI TAGLIO</h2>';
	$tagli = 0;
	$passo = 0;
	// Data una soluzione ottima, finche' non e' intera ...
	while (!$Tableau->check_intera() && $tagli < 25) {
		// aggiungi un vincolo che escluda soluzioni non intere
		$content.= '<h4>Aggiunta di un vincolo:</h4>';
		$content.= $Tableau->aggiungi_vincolo();
		$tagli++;
		// utilizza il metodo del simplesso duale tornare ad una soluzione
		// ottima ammissibile
		$uscita = false;
		$passo = 0;
		do {
			$content.= '<h4>Tableau al passo ' . $passo . ':</h4>';
			$content.= '
    <table summary="mostra il tableau in un lato e nell\'altro il valore delle
    variabili" cellpadding="50%" cellspacing="50%">
     <tbody>
      <tr><td>';
			$content.= $Tableau->display_tableau();
			$content.= '</td>
      <td>';
			$content.= $Tableau->display_status(2);
			$content.= '</td>
      </tr>
     </tbody>
    </table>';
			// un passo di pivoting
			$result = $Tableau->simplesso_duale($i, $j);
			if ($result == 0) {
				$uscita = true;
				$content.= '<font color="red" size="+2">Soluzione <strong>ottima</strong>: &nbsp;&nbsp;&nbsp; </font>';
				$content.= $Tableau->soluzione_ottima();
			} //$result == 0
			else if ($result == - 1) {
				$uscita = true;
				$content.= '<font color="red" size="+2">Soluzione <strong>inammissibile</strong>.<br>L\'algoritmo si arresta.</font><br>';
				scrivi_pagina($content);
				echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
				exit(0);
			} //$result == -1
			else {
				$content.= "Soluzione super-ottima. L'algoritmo continua ad iterare.<br>\n";
				$content.= sprintf("Pivot in riga <strong>r%d</strong> colonna <strong>x%d</strong>.<br>\n", $i, $j);
			}
			$passo++;
		}
		while ($uscita == false && $passo < 25);
	} //!$Tableau->check_intera() && $tagli < 25
	if ($passo == 0) {
		$content.= '<font color="red" size="+2">Soluzione <strong>ottima</strong>: &nbsp;&nbsp;&nbsp; </font>';
		$content.= $Tableau->soluzione_ottima();
	} //$passo == 0
	if ($passo == 25) $content.= sprintf("L'algoritmo termina perch&egrave; raggiunto il numero massimo di iterazioni previste.<br>");
	if ($tagli == 25) $content.= sprintf("L'algoritmo termina perch&egrave; raggiunto il numero massimo di iterazioni previste.<br>");
} // END piani_di_taglio()
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//                                 MAIN                                      //
//                                                                           //
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
/*
 *
 * Se esistono immagini precedenti, cancelliamole.
 *
*/
//|$XDEBUG_SESSION_START="testID";
$tmpimages = glob("images/tmp/tmp*");
if (!empty($tmpimages) > 0) foreach ($tmpimages as $filename) {
	if (isset($filename)) unlink($filename);
} //$tmpimages as $filename
/*
 *
 * Inizializziamo le variabili
 *
*/
init_variables($minmax, $numVariables, $numConstraints, $a, $b, $c, $d, $lge, $intera, $cambia_segno, $grafico, $image, $name);
/*
 *
 * Mostriamo il problema cosi' come e' stato immesso
 *
*/
$content = '<h4>Il problema introdotto &egrave;</h4>';
$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera);
/*
 *
 * Portiamo il problema in forma standard
 *
*/
$tmp = riduci_standard($minmax, $numVariables, $numConstraints, $a, $b, $c, $d, $lge, $intera, $cambia_segno, $base);
/*
 *
 * Mostriamo il problema espresso in FORMA STANDARD
 *
*/
if (!strcmp($tmp, "<p></p>\n\n")) $content.= "<p><strong>La FORMA STANDARD coincide la rappresentazione del problema immessa</strong>.</p>";
else {
	$content.= '<h4>Il problema espresso in FORMA STANDARD</h4>';
	$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera);
	if (isset($verbose)) $content.= $tmp;
}
/*
 *
 * Portiamo il problema in forma canonica
 *
*/
$tmp = riduci_canonica($minmax, $numVariables, $numConstraints, $numArtificials, $a, $b, $c, $d, $lge, $base, $rho);
/*
 *
 * Mostriamo il problema espresso in FORMA CANONICA
 *
*/
if (!strcmp($tmp, "<p></p>\n\n")) $content.= "<p><strong>La FORMA CANONICA coincide con quella STANDARD</strong>.</p>";
else {
	$content.= '<h4>Il problema espresso in FORMA CANONICA</h4>';
	$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera);
	if (isset($verbose)) $content.= $tmp;
}
unset($tmp);
/* se il problema e' a due variabili mostra il grafico
 della regione di ammissibilita' */
if (isset($grafico)) $content.= "<center><img src=\"$name.png\" alt=\"[IMG]  Regione di ammissibilita'\" align=\"middle\"></center>";
if ($numArtificials > 0) {
	/*
	 *
	 * FASE I del METODO delle DUE FASI
	 *
	*/
	crea_tableau_fase_1($rho, $a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, $Tableau);
	$content.= '<h4>Il problema espresso in FORMA CANONICA per la prima fase</h4>';
	$content.= $Tableau->display_equations(2);
	$content.= fase_1($Tableau, $content,$numArtificials);
	// ripristina tableau e variabili di base
	$Tableau->prima_fase($numArtificials);
} //$numArtificials > 0
else crea_tableau_simplesso($a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, $Tableau);
/*
 *
 * Eseguiamo il simplesso
 *
*/
$content.= fase_2($Tableau, $content, $grafico, $name, $image);
if (strcmp($intera, "true")) {
	// il problema di programmazione lineare e' stato risolto
	scrivi_pagina($content);
	echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
	exit(0);
} //strcmp( $intera, "true" )
/*
 *
 * Risoluzione del problema di PLI mediante metodo dei PIANI DI TAGLIO
 *
*/
$content.= piani_di_taglio($Tableau, $content);
scrivi_pagina($content);
echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
exit(0);
?>
