<?php echo "yay";



/// Retrieve from databasic


//     $ismax          Controls whether lp is Maximisation or Minimisation
//     $numVariables    Number of decision variables
//     $numConstraints  Number of constraints (rows in table + 1)
//     $c[]             The cost coefficients of the objective function (Student preferences)
//     $d               The known term of the objective function
//     $a[][]           The original Coefficient
//     $inequal[]       The direction of the inequalities {"=<, >=, ="}
//     $b[]             The vector of resource limitations
//     $entire          The entire linear programming problem


//     $minmax          Controls whether lp is Maximisation or Minimisation
//     $numVariables    Number of decision variables
//     $numConstraints  Number of constraints (rows in table + 1)
//     $c[]             The cost coefficients of the objective function (Student preferences)
//     $d               The known term of the objective function
//     $a[][]           The original Coefficient
//     $inequal[]       The direction of the inequalities {"=<, >=, ="}
//     $b[]             The vector of resource limitations
//     $entire          The entire linear programming problem




function init_variables(&$ismax, &$numVariables, &$numConstraints, &$_a, &$_b,
 &$_c, &$_d, &$inequal, &$entire, &$changeSign, &$graphic, &$image, &$name){
 /* Read the data that has been sent and creates the corresponding variables */ 
	foreach ($_POST as $var => $value) {
		$$var = $value;	
	}

	// Determine if the objective function is required to be negative or not?	
	if (!strcmp($minmax, "max")) $changeSign = true;
	else $changeSign = false;
	
	// generate _a coefficient matrix
	for ($j = 1; $j < $numVariables + 1; $j++) {
		for ($i = 1; $i < $numConstraints + 1; $i++) {
			$_a[$i][$j] = new razionale;
			if (isset($a[$i][$j]))
				$_a[$i][$j]->scanfrac($a[$i][$j]);		
		} // Potential error: $i = 1; $i < $numConstraints + 1; $i++) { for ( //$j = 1; $j < $numVariables + 1; $j++){		
	}
	
	// generate _b resource vector
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		$_b[$i] = new razionale;
		if (isset($b[$i]))
			$_b[$i]->scanfrac($b[$i]);		
	}
	
	// generate _c objective coefficients
	for ($j = 1; $j < $numVariables + 1; $j++) {
		$_c[$j] = new razionale;
		if (isset($c[$j]))
			$_c[$j]->scanfrac($c[$j]);	
	} //Potential error: $j = 1; $j < $numVariables + 1; $j++
	
	// generate _d
	$_d = new razionale;
	if (isset($d)){
		$_d->scanfrac($d);
	}
	
	unset($a);
	unset($b);
	unset($c);
	unset($d);	
} // END init_variables()


function standardise(&$minmax, &$numVariables, $numConstraints, &$a, &$b,
 &$c, &$d, &$inequal, &$entire, &$changeSign, &$basic) {
	// Transform the problem if it's a minimisation
	if (strcmp($minmax, "min")) {
		for ($j = 1; $j < $numVariables + 1; $j++) {
			$c[$j]->negatefrac();
		}
		$d->negatefrac();
		$minmax = "min";
	}
	
	// Add variables slack or surplus variables 
	// Column numVariables $ + $ numAux; 
	// First to be updated and then $a $numAux
	$numAux = 0;
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		if (!strcmp($inequal[$i], "=<")) {
			// slack
			$numAux++;
			$j = $numVariables + $numAux;
			$a[$i][$j] = new razionale(1, 1);
			$inequal[$i] = "=";
			// if b >= 0 -> e' definitely in basic $basic[]=(variable => line)
			if ($b[$i]->value() >= 0) $basic[] = array($i => $j);
				/* this would not be the place for this operation but save a 
				fruitless search in the reduction in canonical form to find out
				that x [$ j] and 'in basic */
		}
		else if (!strcmp($inequal[$i], ">=")) {
			// surplus
			$numAux++;
			$j = $numVariables + $numAux;
			$a[$i][$j] = new razionale(-1, 1);
			$inequal[$i] = "=";
			if ($b[$i]->value() < 0) $basic[] = array($i => $j);
		}		
	}
	$numVariables += $numAux;
} // END standardise()


function nonNegResources(&$a, &$b, &$inequal, $numVariables, $numConstraints) {
	// make b[i] non negative
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		if (isset($b[$i]) and $b[$i]->num() < 0) { // negative resources
			$changeSignConstraint[] = $i;
			// multiply the row by -1
			$b[$i]->negatefrac();
			for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $a[$i][$j]->negatefrac();
			// add changes to the inequality
			if (!strcmp($inequal[$i], "=<")) $inequal[$i] = ">=";
			else if (!strcmp($inequal[$i], ">=")) $inequal[$i] = "=<";
		}		
	}
} // END nonNegResources()


/* returns true if the column variable $ j is a basic variable */
function isBasic($a, $c, $numConstraints, $numVariables, $i, $j)  {
	if (isset($c[$j]) and $c[$j]->num() != 0) return false;
	for ($k = 1; $k < $numConstraints + 1; $k++){
		if ($k != $i)
		if (isset($a[$k][$j]) and $a[$k][$j]->num() != 0)
		return false;
		return true;
	}
}// END isBasic()


/* check if the variable x [$ j] can get into the basic set and possibly 
  perform the operation to find the output */ 
function searchBasic($a, $c, $numConstraints, $numVariables, $i, &$div) {
	$div = new razionale;
	for ($j = 1; $j < $numVariables + 1; $j++) {
		if (!isset($a[$i][$j])) continue;
		$div = $a[$i][$j];
		// if not present in the objective function and other constraints
		if ($div->num() > 0) {
			if (isBasic($a, $c, $numConstraints, $numVariables, $i, $j)) {
				// if constant is already in the basic output the row number 
				return $j;
			} 
		} 
	} 
	//if search failed destroy div
	unset($div);
	return 0;
} // END searchBasic()


function normalise(&$a, &$b, $i, $j, $numVariables) {
	$div = new razionale;
	$div = $a[$i][$j];
	if ($div->value() == 1) return "";
	elseif ($div->num() > 0) {
		// divide the row by the constant $div
		$b[$i]->divfrac($b[$i], $div);
		for ($k = 0; $k < $numVariables + 1; $k++) {
			if (isset($a[$i][$k])) $a[$i][$k]->divfrac($a[$i][$k], $div);
		}
	} //TODO check this
} // END normalise()


function addArtificial($i, $numArtificials, $numVariables, &$a, $b, &$rho, &$basic) {
	$k = $numVariables + $numArtificials;
	$a[$i][$k] = new razionale(1, 1);
	for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) {
		$rho[$j]->subfrac($rho[$j], $a[$i][$j]);
	}
	$rho[0]->subfrac($rho[0], $b[$i]);
	$basic[$i - 1] = $k;
} // END addArtificial()


function gia_in_basic(&$basic, $i) {
	//TODO: check
	//for ($k = 0; $k < count($basic); $k++)
	//foreach($basic[$k] as $key  => $value)
	//if ($value == $i)
	//return true;
	if (isset($basic[$i]) && $basic[$i] != 0) return true;
	return false;
} // END gia_in_basic()


function reduceCanon(&$minmax, &$numVariables, &$numConstraints, &$numArtificials, &$a, &$b, &$c, &$d, &$inequal, &$basic, &$rho) {
	/* a: As=Im; b: cs=0; c: b>=0 */
	$basic = array_fill(0, $numConstraints, 0);
	$numArtificials = 0;
	// Ensure resources are positive by finding basic variables
	for ($i = 1; $i < $numConstraints + 1; $i++) 
		if (!gia_in_basic($basic, $i - 1)) {
			// Occurs if one of the variables can enter the basic set, dividing it in case
			$div = 1;
			if ($j = searchBasic($a, $c, $numConstraints, $numVariables, $i, $div)) {
				// if necessary, normalise the row
				if ($div->value() != 1) $tmp.= normalise($a, $b, $i, $j, $numVariables);
				// add $j in the set of indices for the basic set
				$basic[$i - 1] = $j;
			} //$j = searchBasic( $a, $c, $numConstraints, $numVariables, $i, $div )
			else {
				if (!isset($rho)) for ($k = 0; $k < $numVariables + 1; $k++) $rho[$k] = new razionale(0, 1);
				$tmp.= addArtificial($i, ++$numArtificials, $numVariables, $a, $b, $rho, $basic);
				// TODO: Check!!!!
				$basic[$i - 1] = $numVariables + $numArtificials;
			}
		} //!gia_in_basic( $basic, $i - 1 )
	$numVariables+= $numArtificials;
	return $tmp;
} // END reduceCanon()
function crea_tableau_simplesso($a, $b, $c, $d, $numVariables, $numConstraints, $changeSign, $basic, &$Tableau) {
	$matrice[0][0] = new razionale(-$d->num(), $d->den());
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($c[$j])) $matrice[0][$j] = new razionale($c[$j]->num(), $c[$j]->den());
	for ($i = 0; $i < $numConstraints + 1; $i++) if (isset($b[$i])) $matrice[$i][0] = new razionale($b[$i]->num(), $b[$i]->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $matrice[$i][$j] = new razionale($a[$i][$j]->num(), $a[$i][$j]->den());
	$Tableau = new matrix($numConstraints + 1, $numVariables + 1, $matrice, $basic, $changeSign);
} // END crea_tableau_simplesso()
function crea_tableau_fase_1($rho, $a, $b, $c, $d, $numVariables, $numConstraints, $changeSign, $basic, &$Tableau) {
	// aggiungiamo la forma di inammissibilita'
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($rho[$j])) $matrice[0][$j] = new razionale($rho[$j]->num(), $rho[$j]->den());
	////$matrice[0][0] = new razionale(-$matrice[0][0]->num(), $matrice[0][0]->den());
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($c[$j])) $matrice[1][$j] = new razionale($c[$j]->num(), $c[$j]->den());
	if ($changeSign)
		$matrice[1][0] = new razionale(-$d->num(), $d->den());
	else
		$matrice[1][0] = new razionale($d->num(), $d->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) if (isset($b[$i])) $matrice[$i + 1][0] = new razionale($b[$i]->num(), $b[$i]->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $matrice[$i + 1][$j] = new razionale($a[$i][$j]->num(), $a[$i][$j]->den());
	// cambiare l'assegnamento agli indici di basic
	for ($i = 0; $i < count($basic); $i++) {
		//foreach ($basic[$i] as $key => $value) {
			$basic_df[$i] = $basic[$i];
		//} //$basic[$i] as $key => $value
		
	} //$i = 0; $i < count( $basic ); $i++
	$Tableau = new matrix($numConstraints + 2, $numVariables + 1, $matrice, $basic_df, $changeSign);
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
			if (isset($graphic)) {
				delete($graphic);
				exit(0);
			} //isset( $graphic )
			
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
		if (isset($graphic)) {
			delete($graphic);
			exit(0);
		} //isset( $graphic )
		exit(0);
		// rho < 0		
	} //$p->value() < 0
	else if ($p->value() > 0) {
		$content.= "Caso non previsto: &rho;, per definizione non negativa, &egrave; minore di zero. L'algoritmo &egrave; implementato male.<br>\n";
		scrivi_pagina($content);
		echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
		if (isset($graphic)) {
			delete($graphic);
			exit(0);
		} //isset( $graphic )
		exit(1);		
	} //$p->value() > 0
	else
	// forma inammissibilita' nulla
	// variabili artificiali fuori basic
	if ($Tableau->fuori_basic_artificiali($numArtificials)) $content.= "Tutte le variabili artificiali sono fuori basic<br>\n";
	// variabili artificiali in basic nulle
	else {
		$content.= "Alcune variabili artificiali sono rimaste in basic. Occorrono altre operazioni di pivot.<br>\n";
		// crea l'array che ad ogni variabile x[$j] assegna
		// 0 se $j non e' indice di basic, altrimenti la riga associata.
		for ($j = 1; $j < $Tableau->col; $j++) $arry[$j] = $Tableau->in_basic($j);
		// per ogni variabile artificiale
		for ($j = $Tableau->col - $numArtificials; $j < $Tableau->col; $j++) {
			// se la variabile artificiale x[$j] e' in basic
			if ($arry[$j] >= 0) {
				if ($Tableau->estrai_basic_artificiale($j, $arry[$j], $k, $numArtificials)) {
					$content.= sprintf("Pivot in riga <strong>r%d</strong> colonna <strong>x%d</strong>.<br>", $arry[$j], $k);
					$content.= '<h4>Tableau al passo ' . $passo++ . ':</h4>';
					$content.= $Tableau->display_tableau();
					$content.= $Tableau->display_status(1);
				} //$Tableau->estrai_basic_artificiale( $j, $arry[$j], &$k )
				else {
					$content.= '<strong>Una o pi&ugrave; variabili artificiali sono rimaste in basic.</strong><br />';
					if ($p = 0)
						$content.='La soluzione è univocamente determinata.'; 
					else
					 $content.= 'Ci sono le equazioni ridondanti (linearmente dipendenti dalle altre).<br>Fin\'ora non ho implementato le routine atte allo scopo e l\'algoritmo termina.';
					// scrivi_pagina($content);
					if (isset($graphic)) {
						delete($graphic);
					} //isset( $graphic )
					
					exit(0);
				}
			} //$arry[$j] != 0
			
		} //$j = $Tableau->col - $numArtificials; $j < $Tableau->col; $j++
		
	}
} // END fase_1()
function fase_2(&$Tableau, &$content, $graphic, $name, &$image) {
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
		if (isset($graphic)) {
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
		} //isset( $graphic )
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
	// Data una soluzione ottima, finche' non e' entire ...
	while (!$Tableau->check_entire() && $tagli < 25) {
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
	} //!$Tableau->check_entire() && $tagli < 25
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
init_variables($minmax, $numVariables, $numConstraints, $a, $b, $c, $d, $inequal, $entire, $changeSign, $graphic, $image, $name);
/*
 *
 * Mostriamo il problema cosi' come e' stato immesso
 *
*/
$content = '<h4>Il problema introdotto &egrave;</h4>';
$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $inequal, $b, $entire);
/*
 *
 * Portiamo il problema in forma standard
 *
*/
$tmp = standardise($minmax, $numVariables, $numConstraints, $a, $b, $c, $d, $inequal, $entire, $changeSign, $basic);
/*
 *
 * Mostriamo il problema espresso in FORMA STANDARD
 *
*/
if (!strcmp($tmp, "<p></p>\n\n")) $content.= "<p><strong>La FORMA STANDARD coincide la rappresentazione del problema immessa</strong>.</p>";
else {
	$content.= '<h4>Il problema espresso in FORMA STANDARD</h4>';
	$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $inequal, $b, $entire);
	if (isset($verbose)) $content.= $tmp;
}
/*
 *
 * Portiamo il problema in forma canonica
 *
*/
$tmp = reduceCanon($minmax, $numVariables, $numConstraints, $numArtificials, $a, $b, $c, $d, $inequal, $basic, $rho);
/*
 *
 * Mostriamo il problema espresso in FORMA CANONICA
 *
*/
if (!strcmp($tmp, "<p></p>\n\n")) $content.= "<p><strong>La FORMA CANONICA coincide con quella STANDARD</strong>.</p>";
else {
	$content.= '<h4>Il problema espresso in FORMA CANONICA</h4>';
	$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $inequal, $b, $entire);
	if (isset($verbose)) $content.= $tmp;
}
unset($tmp);
/* se il problema e' a due variabili mostra il graphic
 della regione di ammissibilita' */
if (isset($graphic)) $content.= "<center><img src=\"$name.png\" alt=\"[IMG]  Regione di ammissibilita'\" align=\"middle\"></center>";
if ($numArtificials > 0) {
	/*
	 *
	 * FASE I del METODO delle DUE FASI
	 *
	*/
	crea_tableau_fase_1($rho, $a, $b, $c, $d, $numVariables, $numConstraints, $changeSign, $basic, $Tableau);
	$content.= '<h4>Il problema espresso in FORMA CANONICA per la prima fase</h4>';
	$content.= $Tableau->display_equations(2);
	$content.= fase_1($Tableau, $content,$numArtificials);
	// ripristina tableau e variabili di basic
	$Tableau->prima_fase($numArtificials);
} //$numArtificials > 0
else crea_tableau_simplesso($a, $b, $c, $d, $numVariables, $numConstraints, $changeSign, $basic, $Tableau);
/*
 *
 * Eseguiamo il simplesso
 *
*/
$content.= fase_2($Tableau, $content, $graphic, $name, $image);
if (strcmp($entire, "true")) {
	// il problema di programmazione lineare e' stato risolto
	scrivi_pagina($content);
	echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
	exit(0);
} //strcmp( $entire, "true" )
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
