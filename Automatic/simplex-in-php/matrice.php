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
class matrix {
	public $row; // il numero di righe del tableau
	public $col; // il numero di colonne del tableau
	public $Tableau; // il tableau
	public $S; // $S[] = (indice di base => riga soluzione associata)
	public $cambia_segno; // == true se il problema e' di massimo
	public $sol; // vettore delle soluzioni nella forma [z|x]
	public function matrix($row, $col, $matrice, $S, $cambia_segno) /* costruttore che alloca la matrice A di dimensione [row x col] */ {
		$this->row = $row;
		$this->col = $col;
		$this->Tableau = $matrice;
		$this->S = $S;
		$this->cambia_segno = $cambia_segno;
	}
	/* end Matrix*/
	public function in_S($j) {
		$m = count($this->S);
		for ($i = 0; $i < $m; $i++) if ($j == $this->S[$i]) return $i;
		return -1;
	}
	/*
	 *
	 * Funzioni di output "grafico"
	 *
	*/
	public function display_tableau() /* scrive la matrice $this->Tableau in forma di tabella */ {
		$content = '
        <table frame="box" summary="visualizzazione tableau" cellspacing="5" cellpadding="3">
         <thead>
        ';
		// aggiungi intestazioni
		$content.= '<tr  align="center" valign="middle">
            <th></th><th></th>';
		for ($j = 1; $j < $this->col; $j++)
		// aggiungi elemento di posizione (i,j)
		$content.= sprintf("<th>x%d</th>\n", $j);
		$content.= '</tr>
         </thead>
         <tbody>
         ';
		for ($i = 0; $i < $this->row; $i++) {
			// aggiungi una riga
			$content.= '<tr  align="center"  valign="middle"><td><strong>r' . $i . '</strong></td>';
			for ($j = 0; $j < $this->col; $j++) {
				// aggiungi elemento di posizione (i,j)
				if ($this->in_S($j) >= 0) $bgcolor = "#E8E8E8";
				else $bgcolor = "#FFFFFF";
				if (!isset($this->Tableau[$i][$j])) $content.= sprintf("<td bgcolor=\"$bgcolor\">0</td>\n");
				else $content.= sprintf("<td bgcolor=\"$bgcolor\">%s</td>\n", $this->Tableau[$i][$j]->fractoa());
			} //$j = 0; $j < $this->col; $j++
			$content.= '</tr>';
		} //$i = 0; $i < $this->row; $i++
		$content.= '
         </tbody>
        </table>
        ';
		return $content;
	}
	public function display_equations($fase) /* scrive la matrice $this->Tableau in forma di equazioni */ {
		if ($fase == 1) $content = '
        <strong> min z = ';
		else $content = '
        <strong> min &rho; = &Sigma; &alpha;<sub>i</sub> = ';
		// 1ma riga: c^t x + ...
		if (!isset($this->Tableau[0][1])) $content.= sprintf("&nbsp; &nbsp;");
		else if ($this->Tableau[0][1]->value() == 1) {
			$content.= sprintf(" x<sub>1</sub>");
		} //$this->Tableau[0][1]->value() == 1
		else if ($this->Tableau[0][1]->num() == 0) {
			// $content .= sprintf("&nbsp; &nbsp;");
			
		} //$this->Tableau[0][1]->num() == 0
		else if ($this->Tableau[0][1]->value() == - 1) {
			$content.= sprintf(" - x<sub>1</sub>");
		} //$this->Tableau[0][1]->value() == -1
		else {
			$content.= sprintf(" %s x<sub>1</sub>", $this->Tableau[0][1]->fractoa());
		}
		for ($j = 2; $j < $this->col; $j++) {
			if (!isset($this->Tableau[0][$j])) {
				// $content .= sprintf("&nbsp; &nbsp;");
				
			} //!isset( $this->Tableau[0][$j] )
			else if ($this->Tableau[0][$j]->num() >= 0) {
				if ($this->Tableau[0][$j]->value() == 1) { // = 1
					$content.= sprintf(" + x<sub>%d</sub>", $j);
				} //$this->Tableau[0][$j]->value() == 1
				else if ($this->Tableau[0][$j]->num() == 0) { // = 0
					// $content .= sprintf("&nbsp; &nbsp;");
					
				} //$this->Tableau[0][$j]->num() == 0
				else { // > 0, != 1
					$content.= sprintf(" + %s x<sub>%d</sub>", $this->Tableau[0][$j]->fractoa(), $j);
				}
			} //$this->Tableau[0][$j]->num() >= 0
			else if ($this->Tableau[0][$j]->value() == - 1) { // = -1
				$content.= sprintf(" - x<sub>%d</sub>", $j);
			} //$this->Tableau[0][$j]->value() == -1
			else { // < 0 != -1
				$tmp = new razionale;
				$tmp = clone $this->Tableau[0][$j];
				$tmp->negatefrac();
				$content.= sprintf(" - %s x<sub>%d</sub>", $tmp->fractoa(), $j);
			}
		} //$j = 2; $j < $this->col; $j++
		// ... + (-d)
		if (!isset($this->Tableau[0][0])) {
			// $content .= sprintf("&nbsp; &nbsp;");
			
		} //!isset( $this->Tableau[0][0] )
		else if ($this->Tableau[0][0]->num() > 0) {
			$content.= sprintf(" - %s ", $this->Tableau[0][0]->fractoa());
		} //$this->Tableau[0][0]->num() > 0
		else if ($this->Tableau[0][0]->num() < 0) {
			$tmp = new razionale;
			$tmp = clone $this->Tableau[0][0];
			$tmp->negatefrac();
			$content.= sprintf(" + %s ", $tmp->fractoa());
		} //$this->Tableau[0][0]->num() < 0
		else;
		$content.= '<br><br>
        Soggetto a<br><br>
        ';
		// righe dei vincoli
		for ($i = $fase; $i < $this->row; $i++) {
			// riga del vincolo
			if ($fase == 1) $content.= $i . ') ';
			else $content.= $i - 1 . ') ';
			// variabile 1ma colonna
			if (!isset($this->Tableau[$i][1])) {
				// $content .= sprintf("&nbsp; &nbsp;");
				
			} //!isset( $this->Tableau[$i][1] )
			else if ($this->Tableau[$i][1]->value() == 1) {
				$content.= sprintf(" x<sub>1</sub>");
			} //$this->Tableau[$i][1]->value() == 1
			else if ($this->Tableau[$i][1]->value() == - 1) {
				$content.= sprintf(" - x<sub>1</sub>");
			} //$this->Tableau[$i][1]->value() == -1
			else if ($this->Tableau[$i][1]->num() == 0) {
				// $content .= sprintf("&nbsp; &nbsp;");
				
			} //$this->Tableau[$i][1]->num() == 0
			else {
				$content.= sprintf(" %.s x<sub>1</sub>", $this->Tableau[$i][1]->fractoa());
			}
			// le altre colonne
			for ($j = 2; $j < $this->col; $j++) {
				if (!isset($this->Tableau[$i][$j])) {
					// $content .= sprintf("&nbsp; &nbsp;");
					
				} //!isset( $this->Tableau[$i][$j] )
				else if ($this->Tableau[$i][$j]->num() >= 0) { // >= 0
					if ($this->Tableau[$i][$j]->value() == 1) { // == 1
						$content.= sprintf(" + x<sub>%d</sub>", $j);
					} //$this->Tableau[$i][$j]->value() == 1
					else if ($this->Tableau[$i][$j]->num() == 0) { // == 0
						// $content .= sprintf("&nbsp; &nbsp;");
						
					} //$this->Tableau[$i][$j]->num() == 0
					else { // > 0 != 1
						$content.= sprintf(" + %s x<sub>%d</sub>", $this->Tableau[$i][$j]->fractoa(), $j);
					}
				} //$this->Tableau[$i][$j]->num() >= 0
				else if ($this->Tableau[$i][$j]->value() == - 1) { // == -1
					$content.= sprintf(" - x<sub>%d</sub>", $j);
				} //$this->Tableau[$i][$j]->value() == -1
				else { // < 0 != -1
					$tmp = new razionale;
					$tmp = clone $this->Tableau[$i][$j];
					$tmp->negatefrac();
					$content.= sprintf(" - %s x<sub>%d</sub>", $tmp->fractoa(), $j);
				}
			} //$j = 2; $j < $this->col; $j++
			// risorse
			if (!isset($this->Tableau[$i][0])) $content.= sprintf(" = 0 <br>");
			else $content.= sprintf(" = %s <br>", $this->Tableau[$i][0]->fractoa());
		} //$i = $fase; $i < $this->row; $i++
		// non negativita'
		$content.= '
        &nbsp; &nbsp; x<sub>i</sub> &gt;= 0';
		// ed eventuale interezza
		if (isset($intera) && !strcmp($intera, "true")) $content.= ' e INTERI';
		$var = $this->col - 1;
		$content.= ' &nbsp; per i =1,...,' . $var . '</strong>
        ';
		return $content;
	}
	public function display_status($fase) /* scrive il valore della f.o. e delle variabili */ {
		// scrive gli indici di base
		$content = 'Indici di base: S = { ';
		for ($i = 0; $i < count($this->S); $i++) $content.= $this->S[$i] . ', ';
		//foreach ( $this->S[$i] as $key => $value) {
		//    $base[$i] = $this->S[$i];
		//}
		// sort($base);
		//for ($i=0; $i<count($base); $i++)
		//    $content .= $base[$i] . ', ';
		//unset ($base);
		// scrive la soluzione di base: il valore di z o di rho
		$content = substr_replace($content, ' }', strlen($content) - 2);
		$content.= ' <br>
        Soluzione di base: ';
		if ($fase == 1) {
			$p = new razionale(-$this->Tableau[0][0]->num(), $this->Tableau[0][0]->den());
			$content.= sprintf("&rho; = %s <br>", $p->fractoa());
			// scrive la soluzione di base: variabili in e fuori base
			for ($j = 1; $j < $this->col; $j++) {
				$i = $this->in_S($j);
				if ($i >= 0) { // in base?
					if (isset($i)) $content.= sprintf("x<sub>%d</sub> = %s <br>", $j, $this->Tableau[$i + 2][0]->fractoa());
					else echo "in_base NON FUNZIONA<br />\n";
					$this->sol[$j] = clone $this->Tableau[$i + 2][0];
				} //$i >= 0
				else {
					$content.= sprintf("x<sub>%d</sub> = 0 <br>", $j);
					$this->sol[$j] = new razionale(0, 1);
				}
			}
		} //$fase == 1
		else { // seconda fase
			if ($this->cambia_segno == false) // problema di min
			$this->sol[0] = new razionale(-$this->Tableau[0][0]->num(), $this->Tableau[0][0]->den());
			else { // problema di max
				$this->sol[0] = new razionale($this->Tableau[0][0]->num(), $this->Tableau[0][0]->den());
			}
			$content.= sprintf("z = %s <br>", $this->sol[0]->fractoa());
			// scrive la soluzione di base: variabili in e fuori base
			for ($j = 1; $j < $this->col; $j++) {
				$i = $this->in_S($j);
				if ($i >= 0) { // in base? Se si' $i = riga risorsa
					if (isset($i)) $content.= sprintf("x<sub>%d</sub> = %s <br>", $j, $this->Tableau[$i + 1][0]->fractoa());
					else echo "in_base NON FUNZIONA<br />\n";
					$this->sol[$j] = clone $this->Tableau[$i + 1][0];
				} //$i >= 0
				else {
					$content.= sprintf("x<sub>%d</sub> = 0 <br>", $j);
					$this->sol[$j] = new razionale(0, 1);
				}
			}
		}
		return $content;
	}
	public function soluzione_ottima() {
		$content = '<font color="#0000FF" size="+2">';
		$content.= sprintf("z<sup>*</sup> = %s, &nbsp;&nbsp;&nbsp; x<sup>*</sup> = [ ", $this->sol[0]->fractoa());
		for ($j = 1; $j < $this->col - 1; $j++) $content.= sprintf("%s, ", $this->sol[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup>", $this->sol[$j]->fractoa());
		$content.= "</font><br>\n";
		return $content;
	}
	public function soluzioni_ottime($verticeA, $verticeB) {
		$content = '<font color="#0000FF" size="+2">';
		$content.= sprintf("z<sup>*</sup> = %s, &nbsp;&nbsp;&nbsp; x<sup>*</sup> = &lambda; [ ", $verticeA[0]->fractoa());
		for ($j = 1; $j < $this->col - 1; $j++) $content.= sprintf("%s, ", $verticeA[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup> + (1-&lambda;) [ ", $verticeB[0]->fractoa());
		for ($j = 1; $j < $this->col - 1; $j++) $content.= sprintf("%s, ", $verticeB[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup>", $verticeB[$j]->fractoa());
		$content.= "</font><br>\n";
		return $content;
	}
	/*
	 *
	 * Funzioni per l'esecuzione del metodo del simplesso
	 *
	*/
	public function in_base($var) /* Se la variabile x[$var] e' in base ritorna la riga a cui e' associata */ /* Attenzione: richiede che sia stato chiamato display_status(2); */ {
		for ($i = 0; $i < count($this->S); $i++)
		//foreach ( $this->S[$i] as $key => $value) {
		//    if ($key == $var)
		//        return $value;
		if ($this->S[$i] == $var) return $i;
		//}
		return -1;
	}
	public function riduci_tableau($varArtificials) /* Cambia il tableau del metodo delle 2 fasi per eseguire il m. del simplesso */ {
		// le colonne delle variabili artificiali non mi servono piu'
		for ($i = 0; $i < $this->row; $i++) for ($j = $this->col - $varArtificials; $j < $this->col; $j++) unset($this->Tableau[$i][$j]);
		$this->col-= $varArtificials;
		// Alza la matrice di una riga
		for ($i = 0; $i < $this->row; $i++) for ($j = 0; $j < $this->col; $j++) $this->Tableau[$i - 1][$j] = $this->Tableau[$i][$j];
		// le righe sono diminuite di uno
		// unset($this->Tableau[$this->row]);
		$this->row--;
	}
	public function correggi_base() /* ripristina $this->S dalla prima fase al simplesso*/ {
		//for ($i=0; $i<count($this->S); $i++) {
		//foreach ($this->S[$i] as $key => $value) {
		//    $base_pf[] = array($key => --$value);
		//}
		//}
		//$this->S = $base_pf;
		
	}
	public function prima_fase($numArtificials) /* ripristina i dati per l'esecuzione della seconda fase */ {
		$this->riduci_tableau($numArtificials);
		$this->correggi_base();
	}
	public function fuori_base_artificiali($numArtificials) /* verifica se le variabili artificiali sono fuori base */ {
		for ($j = $this->col - $numArtificials; $j < $this->col; $j++) if ($this->in_base($j) >= 0) return false;
		return true;
	}
	public function estrai_base_artificiale($j, $h, &$k, $numArtificials) /* porta la variabile artificiale x[$j] fuori base, se possibile */ {
		// crea l'array che ad ogni variabile x[$n] assegna
		// -1 se $n non e' indice di base, altrimenti la riga associata.
		for ($n = 1; $n < $this->col; $n++) $arry[$n] = $this->in_base($n);
		// prendi la prima variabile non di base x[$k] per cui a[$h][$k]!=0
		$k = 0;
		while ($k < $this->col - 1) {
			$k++;
			if ($arry[$k] < 0 && isset($this->Tableau[$h][$k]) && $this->Tableau[$h][$k]->num() > 0) break;
		} //$k < $this->col
		// che non sia una variabile artificiale
		if ($k >= $this->col - $numArtificials)
			return false;
		// fai pivot
		$this->pivot($h, $k, 1);

		return true;
	}
	public function unica() /* Restituisce true se la soluzione ottima e' unica */ {
		// se esiste indice non di base k tale che c[k]==0
		for ($j = 1; $j < $this->col; $j++) if ($this->in_S($j) < 0) // $i = riga risorsa
		if (!isset($this->Tableau[0][$j]) || $this->Tableau[0][$j]->num() == 0) return false;
		return true;
	}
	public function altra_soluzione(&$i, &$j, $fase) {
		// se esiste indice non di base k tale che c[k]==0
		for ($j = 1; $j < $this->col; $j++) {
			if ($this->in_S($j) < 0)
			 if (!isset($this->Tableau[0][$j]) || $this->Tableau[0][$j]->num() == 0) {
				// x[$j] prima variabile non di base con c nullo
				// cerca elemento di pivot con min b[i]/a[i][j]
				$h = - 1;
				$min = 10e9; // sembra un valore sufficientemente grande, no?
				for ($i = 1; $i < $this->row; $i++) {
					if (isset($this->Tableau[$i][$j])) if ($this->Tableau[$i][$j]->num() > 0) {
						$quo = $this->Tableau[$i][0]->value() / $this->Tableau[$i][$j]->value();
						if ($quo < $min) {
							$h = $i;
							$min = $quo;
						} //$quo < $min
						
					} //$this->Tableau[$i][$j]->num() > 0
					
				} //$i = 1; $i < $this->row; $i++
				if ($h == - 1) // a[i][j] =< 0 per ogni i
				continue; // soluzione illimitata
				// ora h e' la riga per cui b[i]/a[i][j] e' min
				$this->pivot($h, $j, $fase);
				// le variabili di base sono cambiate: entra x[j] ed esce x[i]/i in S,(i => j)
				/*for ($i=0; $i<count($this->S); $i++) {
				                foreach ($this->S[$i] as $key => $value) {
				                if ($value == $h) {
				                $this->S[] = array($j => $h); // entra x[j]
				                array_pop($this->S[$i]); // esce x[i]
				                // forza l'uscita dal ciclo
				                $i=count($this->S);
				                }
				                }
				                }
				*/
				$i = $h;
				break;
			} //!isset( $this->Tableau[0][$j] ) || $this->Tableau[0][$j]->num() == 0
			
		}
	}
	/*
	 *
	 * Operazioni su matrice
	 *
	*/
	public function pivot($h, $k, $fase) /* Pivoting, l'operazione fondamentale nell'algoritmo del simplesso */ {
		$pivot = new razionale($this->Tableau[$h][$k]->num(), $this->Tableau[$h][$k]->den());
		// divide la riga $h per se stessa
		for ($j = 0; $j < $this->col; $j++) if (isset($this->Tableau[$h][$j]) && $this->Tableau[$h][$j]->num != 0) $this->Tableau[$h][$j]->divfrac($this->Tableau[$h][$j], $pivot);
		// per ogni riga
		for ($i = 0; $i < $this->row; $i++)
		// diversa da $h
		if ($i != $h) {
			if (isset($this->Tableau[$i][$k])) $m = new razionale($this->Tableau[$i][$k]->num(), $this->Tableau[$i][$k]->den());
			for ($j = 0; $j < $this->col; $j++)
			// se e' la colonna $k allora risparmia il conto
			if ($j == $k) if (isset($this->Tableau[$i][$j])) $this->Tableau[$i][$j]->set(0, 1);
			else $this->Tableau[$i][$j] = new razionale(0, 1);
			// altrimenti fai i calcoli
			else {
				$tmp = new razionale;
				if (isset($m)) {
					if (!isset($this->Tableau[$h][$j])) $this->Tableau[$h][$j] = new razionale();
					$tmp->mulfrac($m, $this->Tableau[$h][$j]);
					if (isset($this->Tableau[$i][$j])) $this->Tableau[$i][$j]->subfrac($this->Tableau[$i][$j], $tmp);
					else $this->Tableau[$i][$j] = new razionale(-$tmp->num(), $tmp->den());
				} //isset( $m )
				
			} //$j = 0; $j < $this->col; $j++
			
		} //$i != $h
		if ($fase == 1) $this->S[$h - 2] = $k;
		else $this->S[$h - 1] = $k;
	}
	public function simplesso(&$row, &$col, $fase) /* Sceglie il pivot e aggiorna la matrice */ {
		// usate come costanti
		$ottima = 0;
		$illimitata = - 1;
		$migliorabile = 1;
		$k = - 1;
		$min = 1;
		// a partire dalla colonna 1 perche' per ogni c[j] (non di base)
		for ($j = 1; $j < $this->col; $j++) {
			if (($this->in_S($j) < 0)) {
				if ( ! isset($this->Tableau[0][$j])) {
					$this->Tableau[0][$j] = new razionale;
				}
				if ($this->Tableau[0][$j]->num() < 0 && $this->Tableau[0][$j]->value() < $min) {
				$k = $j;
				$min = $this->Tableau[0][$j]->value();
				} //$this->Tableau[0][$j]->num() < 0 && $this->Tableau[0][$j]->value() < $min
			}
			
		} //$j = 1; $j < $this->col; $j++
		if ($k == - 1) // c[j] >= 0 per ogni j
		return $ottima; // soluzione ottima
		// ora k e' la colonna in cui c[j] assume valore negativo max in modulo
		$h = - 1;
		$min = 10e9; // sembra un valore sufficientemente grande, no?
		// Se siamo nella prima fase del metodo delle due fasi occorre prestare
		// attenzione a scegliere elementi non appartenenti alla prima riga
		// se il termine di indice (0,1) e' =< 0
		for ($fase == 1 ? $i = 2 : $i = 1; $i < $this->row; $i++) {
			if (isset($this->Tableau[$i][$k])) if ($this->Tableau[$i][$k]->num() > 0) {
				$quo = $this->Tableau[$i][0]->value() / $this->Tableau[$i][$k]->value();
				if ($quo < $min) {
					$h = $i;
					$min = $quo;
				} //$quo < $min
				
			} //$this->Tableau[$i][$k]->num() > 0
			
		} //$fase == 1 ? $i = 2 : $i = 1; $i < $this->row; $i++
		if ($h == - 1) // a[i][k] =< 0 per ogni i
		return $illimitata; // soluzione illimitata
		// ora h e' la riga per cui b[i]/a[i][k] e' min
		$this->pivot($h, $k, $fase);
		$row = $h;
		$col = $k;
		return $migliorabile;
	}
	public function simplesso_duale(&$row, &$col) /* Sceglie il pivot per il metodo duale del simplesso e aggiorna la matrice */ {
		// usate come costanti
		$ottima = 0;
		$inammissibile = - 1;
		$nonammissibile = 1;
		$h = - 1;
		$min = 1;
		// si valutano i b[i] alla ricerca di quello negatvo con modulo max
		for ($i = 1; $i < $this->row; $i++) {
			if ($this->Tableau[$i][0]->num() < 0 && $this->Tableau[$i][0]->value() < $min) {
				$h = $i;
				$min = $this->Tableau[$i][0];
			} //$this->Tableau[$i][0]->num() < 0 && $this->Tableau[$i][0]->value() < $min
			
		} //$i = 1; $i < $this->row; $i++
		if ($h == - 1) // b[i] >= 0 per ogni j
		return $ottima; // soluzione ottima
		// ora h e' la riga in cui b[i] assume valore negativo max in modulo
		$k = - 1;
		$min = 10e9; // sembra un valore sufficientemente grande, no?
		for ($j = 1; $j < $this->col; $j++) {
			if ($this->Tableau[$h][$j]->num() < 0) {
				$quo = $this->Tableau[0][$j]->value() / (-$this->Tableau[$h][$j]->value());
				if ($quo < $min) {
					$k = $j;
					$min = $quo;
				} //$quo < $min
				
			} //$this->Tableau[$h][$j]->num() < 0
			
		} //$j = 1; $j < $this->col; $j++
		if ($k == - 1) // a[h][j] > 0 per ogni j
		return $inammissibile; // soluzione inammissibile
		// ora h e' la riga per cui c[k]/(-a[h][k]) e' min
		//$this->S[] = 0;
		$this->pivot($h, $k, 2);

		$row = $h;
		$col = $k;
		return $nonammissibile;
	}
	/*
	 *
	 * Relazioni con l'esterno
	 *
	*/
	public function elemento($i, $j) /*  Restituisce l'elemento in posizione [i][j] */ {
		return $this->Tableau[$i][$j];
	}
	public function check_intera() /* restituisce true se la soluzione e' intera */ {
		// devono essere intere sia z
		if ($this->sol[0]->den() != 1) return false;
		// sia tutte le variabili in base
		for ($j = 0; $j < $this->col; $j++) {
			$i = $this->in_base($j);
			if ($i != 0) {
				if (isset($this->sol[$i]->den)) if ($this->sol[$i]->den() != 1) {
					return false;
				} //$this->sol[$i]->den() != 1
				
			} //$i != 0
			
		} //$j = 0; $j < $this->col; $j++
		return true;
	}
	public function frac($x) /* ritorna la parte frazionaria */ {
		$int = floor($x->value());
		$f = new razionale($int, 1);
		$f->subfrac($x, $f);
		return $f;
	}
	public function aggiungi_vincolo() /* genera e aggiunge il vincolo del metodo dei piani di taglio */ {
		// cerca la riga con max parte frazionaria
		$f = new razionale;
		$frac = new razionale;
		$h = - 1;
		$max = 0;
		for ($i = 0; $i < $this->row; $i++) {
			$frac = $this->frac($this->Tableau[$i][0]);
			if ($frac->value() > $max) {
				$max = $frac->value();
				$h = $i;
			} //$frac->value() > $max
			
		} //$i = 0; $i < $this->row; $i++
		if ($h == - 1) return "Il programma &egrave; implementato male: non trova vincoli non interi";
		else $content = '<p>Riga con f<sub>i</sub> massima: <strong>' . $h . '</strong></p>';
		// genera e scrive il vincolo
		$f = clone $this->frac($this->Tableau[$h][0]);
		$this->Tableau[$this->row][0] = new razionale(-$f->num(), $f->den());
		$f = $this->frac($this->Tableau[$h][1]);
		$this->Tableau[$this->row][1] = new razionale(-$f->num(), $f->den());
		if ($this->Tableau[$this->row][1]->num() != 0) {
			$f->set(-$this->Tableau[$this->row][1]->num(), $this->Tableau[$this->row][1]->den());
			$content.= sprintf(" %s x<sub>1</sub>", $f->fractoa());
		} //$this->Tableau[$this->row][1]->num() != 0
		for ($j = 2; $j < $this->col; $j++) {
			$f = clone $this->frac($this->Tableau[$h][$j]);
			$this->Tableau[$this->row][$j] = new razionale(-$f->num(), $f->den());
			if ($this->Tableau[$this->row][$j]->num() != 0) {
				$f->set(-$this->Tableau[$this->row][$j]->num(), $this->Tableau[$this->row][$j]->den());
				$content.= sprintf(" + %s x<sub>%d</sub>", $f->fractoa(), $j);
			} //$this->Tableau[$this->row][$j]->num() != 0
			
		} //$j = 2; $j < $this->col; $j++
		$f->set(-$this->Tableau[$this->row][0]->num, $this->Tableau[$this->row][0]->den());
		$content.= sprintf(" &gt;= %s", $f->fractoa());
		$this->Tableau[$this->row][$this->col] = new razionale(1, 1);
		// aggiungi anche variabile di base inammissibile
		$this->S[] = $this->col;
		$this->row++;
		$this->col++;
		return $content;
	}
}
?>

