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

require_once "rationalNum.php";

class matrix {
	public $rows; // the row number of the table
	public $cols; // the column number of the table
	public $table; // the tabluea
	public $S; // $S[] = base index holds associated solution for the row 
	public $changeSign; // boolean variable == true if the problem is a max
	public $solutionVector; // Solution vector of the form [z|x]

    // Constructor that allocates the matrix A [row x col]
	public function __construct($rows, $cols, $matrix, $S, $changeSign){
		$this->rows = $rows;
		$this->cols = $cols;
		$this->table = $matrix;
		$this->S = $S;
		$this->changeSign = $changeSign;
	}

    // End matrix. Find if $j is in solution vector and returns the index, else
    // returns -1
	public function inS($j) {
		$m = count($this->S);
		// iterate through S array to find j and return index
		for ($i = 0; $i < $m; $i++) {
            if ($j == $this->S[$i]) {
                return $i;
            }
        }
		return -1;
	}

	// Visual output function. Writes the array $this->table in html table form
	public function display_table() {
		$content = '<table><thead>';

		// adding headers
		$content.= '<tr><th></th><th></th>';
		for ($j = 1; $j < $this->cols; $j++) {
            // Add element into position i,j
            $content.= sprintf("<th>x%d</th>\n", $j);
        }
		$content.= '</tr></thead><tbody>';

        for ($i = 0; $i < $this->rows; $i++) {
			// Adding a line
			$content.= '<tr><td><strong>r' . $i . '</strong></td>';

			for ($j = 0; $j < $this->cols; $j++) {
				// Add element into position i,j
				if ($this->inS($j) >= 0) {
                    $bgcolor = "#E8E8E8";
                } else {
                    $bgcolor = "#FFFFFF";
                }

				if (!isset($this->table[$i][$j])) {
                    $content.= sprintf("<td bgcolor=\"$bgcolor\">0</td>\n");
                } else {
                    $content.= sprintf("<td bgcolor=\"$bgcolor\">%s</td>\n", $this->table[$i][$j]->fractoa());
                }
			}
			$content.= '</tr>';
		}
		$content.= '</tbody></table>';

		return $content;
	}
	
	public function display_equations($fase) /* scrive la matrice $this->table in forma di equazioni */ {
		if ($fase == 1) {
            $content = '<strong> min z = ';
        } else {
            $content = '<strong> min &rho; = &Sigma; &alpha;<sub>i</sub> = ';
        }
		// 1ma riga: c^t x + ...
		if (!isset($this->table[0][1])){
            $content.= sprintf("&nbsp; &nbsp;");
        } else if ($this->table[0][1]->value() == 1) {
			$content.= sprintf(" x<sub>1</sub>");
		} else if ($this->table[0][1]->num() == 0) { //$this->table[0][1]->value() == 1
			// $content .= sprintf("&nbsp; &nbsp;");
		} else if ($this->table[0][1]->value() == - 1) { //$this->table[0][1]->num() == 0
			$content.= sprintf(" - x<sub>1</sub>");
		} //$this->table[0][1]->value() == -1
		else {
			$content.= sprintf(" %s x<sub>1</sub>", $this->table[0][1]->fractoa());
		}
		for ($j = 2; $j < $this->cols; $j++) {
			if (!isset($this->table[0][$j])) {
				// $content .= sprintf("&nbsp; &nbsp;");
				
			} //!isset( $this->table[0][$j] )
			else if ($this->table[0][$j]->num() >= 0) {
				if ($this->table[0][$j]->value() == 1) { // = 1
					$content.= sprintf(" + x<sub>%d</sub>", $j);
				} //$this->table[0][$j]->value() == 1
				else if ($this->table[0][$j]->num() == 0) { // = 0
					// $content .= sprintf("&nbsp; &nbsp;");
					
				} //$this->table[0][$j]->num() == 0
				else { // > 0, != 1
					$content.= sprintf(" + %s x<sub>%d</sub>", $this->table[0][$j]->fractoa(), $j);
				}
			} //$this->table[0][$j]->num() >= 0
			else if ($this->table[0][$j]->value() == - 1) { // = -1
				$content.= sprintf(" - x<sub>%d</sub>", $j);
			} //$this->table[0][$j]->value() == -1
			else { // < 0 != -1
				$tmp = new rationalNum;
				$tmp = clone $this->table[0][$j];
				$tmp->negatefrac();
				$content.= sprintf(" - %s x<sub>%d</sub>", $tmp->fractoa(), $j);
			}
		} //$j = 2; $j < $this->cols; $j++
		// ... + (-d)
		if (!isset($this->table[0][0])) {
			// $content .= sprintf("&nbsp; &nbsp;");
			
		} //!isset( $this->table[0][0] )
		else if ($this->table[0][0]->num() > 0) {
			$content.= sprintf(" - %s ", $this->table[0][0]->fractoa());
		} //$this->table[0][0]->num() > 0
		else if ($this->table[0][0]->num() < 0) {
			$tmp = new rationalNum;
			$tmp = clone $this->table[0][0];
			$tmp->negatefrac();
			$content.= sprintf(" + %s ", $tmp->fractoa());
		} //$this->table[0][0]->num() < 0
		else;
		$content.= '<br><br>
        Soggetto a<br><br>
        ';
		// righe dei vincoli
		for ($i = $fase; $i < $this->rows; $i++) {
			// riga del vincolo
			if ($fase == 1) $content.= $i . ') ';
			else $content.= $i - 1 . ') ';
			// variabile 1ma colonna
			if (!isset($this->table[$i][1])) {
				// $content .= sprintf("&nbsp; &nbsp;");
				
			} //!isset( $this->table[$i][1] )
			else if ($this->table[$i][1]->value() == 1) {
				$content.= sprintf(" x<sub>1</sub>");
			} //$this->table[$i][1]->value() == 1
			else if ($this->table[$i][1]->value() == - 1) {
				$content.= sprintf(" - x<sub>1</sub>");
			} //$this->table[$i][1]->value() == -1
			else if ($this->table[$i][1]->num() == 0) {
				// $content .= sprintf("&nbsp; &nbsp;");
				
			} //$this->table[$i][1]->num() == 0
			else {
				$content.= sprintf(" %.s x<sub>1</sub>", $this->table[$i][1]->fractoa());
			}
			// le altre colonne
			for ($j = 2; $j < $this->cols; $j++) {
				if (!isset($this->table[$i][$j])) {
					// $content .= sprintf("&nbsp; &nbsp;");
					
				} //!isset( $this->table[$i][$j] )
				else if ($this->table[$i][$j]->num() >= 0) { // >= 0
					if ($this->table[$i][$j]->value() == 1) { // == 1
						$content.= sprintf(" + x<sub>%d</sub>", $j);
					} //$this->table[$i][$j]->value() == 1
					else if ($this->table[$i][$j]->num() == 0) { // == 0
						// $content .= sprintf("&nbsp; &nbsp;");
						
					} //$this->table[$i][$j]->num() == 0
					else { // > 0 != 1
						$content.= sprintf(" + %s x<sub>%d</sub>", $this->table[$i][$j]->fractoa(), $j);
					}
				} //$this->table[$i][$j]->num() >= 0
				else if ($this->table[$i][$j]->value() == - 1) { // == -1
					$content.= sprintf(" - x<sub>%d</sub>", $j);
				} //$this->table[$i][$j]->value() == -1
				else { // < 0 != -1
					$tmp = new rationalNum;
					$tmp = clone $this->table[$i][$j];
					$tmp->negatefrac();
					$content.= sprintf(" - %s x<sub>%d</sub>", $tmp->fractoa(), $j);
				}
			} //$j = 2; $j < $this->cols; $j++
			// risorse
			if (!isset($this->table[$i][0])) $content.= sprintf(" = 0 <br>");
			else $content.= sprintf(" = %s <br>", $this->table[$i][0]->fractoa());
		} //$i = $fase; $i < $this->rows; $i++
		// non negativita'
		$content.= '
        &nbsp; &nbsp; x<sub>i</sub> &gt;= 0';
		// ed eventuale interezza
		if (isset($intera) && !strcmp($intera, "true")) $content.= ' e INTERI';
		$var = $this->cols - 1;
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
			$p = new rationalNum(-$this->table[0][0]->num(), $this->table[0][0]->den());
			$content.= sprintf("&rho; = %s <br>", $p->fractoa());
			// scrive la soluzione di base: variabili in e fuori base
			for ($j = 1; $j < $this->cols; $j++) {
				$i = $this->inS($j);
				if ($i >= 0) { // in base?
					if (isset($i)) $content.= sprintf("x<sub>%d</sub> = %s <br>", $j, $this->table[$i + 2][0]->fractoa());
					else echo "in_base NON FUNZIONA<br />\n";
					$this->sol[$j] = clone $this->table[$i + 2][0];
				} //$i >= 0
				else {
					$content.= sprintf("x<sub>%d</sub> = 0 <br>", $j);
					$this->sol[$j] = new rationalNum(0, 1);
				}
			}
		} //$fase == 1
		else { // seconda fase
			if ($this->changeSign == false) // problema di min
			$this->sol[0] = new rationalNum(-$this->table[0][0]->num(), $this->table[0][0]->den());
			else { // problema di max
				$this->sol[0] = new rationalNum($this->table[0][0]->num(), $this->table[0][0]->den());
			}
			$content.= sprintf("z = %s <br>", $this->sol[0]->fractoa());
			// scrive la soluzione di base: variabili in e fuori base
			for ($j = 1; $j < $this->cols; $j++) {
				$i = $this->inS($j);
				if ($i >= 0) { // in base? Se si' $i = riga risorsa
					if (isset($i)) $content.= sprintf("x<sub>%d</sub> = %s <br>", $j, $this->table[$i + 1][0]->fractoa());
					else echo "in_base NON FUNZIONA<br />\n";
					$this->sol[$j] = clone $this->table[$i + 1][0];
				} //$i >= 0
				else {
					$content.= sprintf("x<sub>%d</sub> = 0 <br>", $j);
					$this->sol[$j] = new rationalNum(0, 1);
				}
			}
		}
		return $content;
	}
	public function soluzione_ottima() {
		$content = '<font color="#0000FF" size="+2">';
		$content.= sprintf("z<sup>*</sup> = %s, &nbsp;&nbsp;&nbsp; x<sup>*</sup> = [ ", $this->sol[0]->fractoa());
		for ($j = 1; $j < $this->cols - 1; $j++) $content.= sprintf("%s, ", $this->sol[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup>", $this->sol[$j]->fractoa());
		$content.= "</font><br>\n";
		return $content;
	}
	public function soluzioni_ottime($verticeA, $verticeB) {
		$content = '<font color="#0000FF" size="+2">';
		$content.= sprintf("z<sup>*</sup> = %s, &nbsp;&nbsp;&nbsp; x<sup>*</sup> = &lambda; [ ", $verticeA[0]->fractoa());
		for ($j = 1; $j < $this->cols - 1; $j++) $content.= sprintf("%s, ", $verticeA[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup> + (1-&lambda;) [ ", $verticeB[0]->fractoa());
		for ($j = 1; $j < $this->cols - 1; $j++) $content.= sprintf("%s, ", $verticeB[$j]->fractoa());
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
	public function riduci_table($varArtificials) /* Cambia il table del metodo delle 2 fasi per eseguire il m. del simplesso */ {
		// le colonne delle variabili artificiali non mi servono piu'
		for ($i = 0; $i < $this->rows; $i++) for ($j = $this->cols - $varArtificials; $j < $this->cols; $j++) unset($this->table[$i][$j]);
		$this->cols-= $varArtificials;
		// Alza la matrice di una riga
		for ($i = 0; $i < $this->rows; $i++) for ($j = 0; $j < $this->cols; $j++) $this->table[$i - 1][$j] = $this->table[$i][$j];
		// le righe sono diminuite di uno
		// unset($this->table[$this->rows]);
		$this->rows--;
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
		$this->riduci_table($numArtificials);
		$this->correggi_base();
	}
	public function fuori_base_artificiali($numArtificials) /* verifica se le variabili artificiali sono fuori base */ {
		for ($j = $this->cols - $numArtificials; $j < $this->cols; $j++) if ($this->in_base($j) >= 0) return false;
		return true;
	}
	public function estrai_base_artificiale($j, $h, &$k, $numArtificials) /* porta la variabile artificiale x[$j] fuori base, se possibile */ {
		// crea l'array che ad ogni variabile x[$n] assegna
		// -1 se $n non e' indice di base, altrimenti la riga associata.
		for ($n = 1; $n < $this->cols; $n++) $arry[$n] = $this->in_base($n);
		// prendi la prima variabile non di base x[$k] per cui a[$h][$k]!=0
		$k = 0;
		while ($k < $this->cols - 1) {
			$k++;
			if ($arry[$k] < 0 && isset($this->table[$h][$k]) && $this->table[$h][$k]->num() > 0) break;
		} //$k < $this->cols
		// che non sia una variabile artificiale
		if ($k >= $this->cols - $numArtificials)
			return false;
		// fai pivot
		$this->pivot($h, $k, 1);

		return true;
	}
	public function unica() /* Restituisce true se la soluzione ottima e' unica */ {
		// se esiste indice non di base k tale che c[k]==0
		for ($j = 1; $j < $this->cols; $j++) if ($this->inS($j) < 0) // $i = riga risorsa
		if (!isset($this->table[0][$j]) || $this->table[0][$j]->num() == 0) return false;
		return true;
	}
	public function altra_soluzione(&$i, &$j, $fase) {
		// se esiste indice non di base k tale che c[k]==0
		for ($j = 1; $j < $this->cols; $j++) {
			if ($this->inS($j) < 0)
			 if (!isset($this->table[0][$j]) || $this->table[0][$j]->num() == 0) {
				// x[$j] prima variabile non di base con c nullo
				// cerca elemento di pivot con min b[i]/a[i][j]
				$h = - 1;
				$min = 10e9; // sembra un valore sufficientemente grande, no?
				for ($i = 1; $i < $this->rows; $i++) {
					if (isset($this->table[$i][$j])) if ($this->table[$i][$j]->num() > 0) {
						$quo = $this->table[$i][0]->value() / $this->table[$i][$j]->value();
						if ($quo < $min) {
							$h = $i;
							$min = $quo;
						} //$quo < $min
						
					} //$this->table[$i][$j]->num() > 0
					
				} //$i = 1; $i < $this->rows; $i++
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
			} //!isset( $this->table[0][$j] ) || $this->table[0][$j]->num() == 0
			
		}
	}
	/*
	 *
	 * Operazioni su matrice
	 *
	*/
	public function pivot($h, $k, $fase) /* Pivoting, l'operazione fondamentale nell'algoritmo del simplesso */ {
		$pivot = new rationalNum($this->table[$h][$k]->num(), $this->table[$h][$k]->den());
		// divide la riga $h per se stessa
		for ($j = 0; $j < $this->cols; $j++) if (isset($this->table[$h][$j]) && $this->table[$h][$j]->num != 0) $this->table[$h][$j]->divfrac($this->table[$h][$j], $pivot);
		// per ogni riga
		for ($i = 0; $i < $this->rows; $i++)
		// diversa da $h
		if ($i != $h) {
			if (isset($this->table[$i][$k])) $m = new rationalNum($this->table[$i][$k]->num(), $this->table[$i][$k]->den());
			for ($j = 0; $j < $this->cols; $j++)
			// se e' la colonna $k allora risparmia il conto
			if ($j == $k) if (isset($this->table[$i][$j])) $this->table[$i][$j]->set(0, 1);
			else $this->table[$i][$j] = new rationalNum(0, 1);
			// altrimenti fai i calcoli
			else {
				$tmp = new rationalNum;
				if (isset($m)) {
					if (!isset($this->table[$h][$j])) $this->table[$h][$j] = new rationalNum();
					$tmp->mulfrac($m, $this->table[$h][$j]);
					if (isset($this->table[$i][$j])) $this->table[$i][$j]->subfrac($this->table[$i][$j], $tmp);
					else $this->table[$i][$j] = new rationalNum(-$tmp->num(), $tmp->den());
				} //isset( $m )
				
			} //$j = 0; $j < $this->cols; $j++
			
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
		for ($j = 1; $j < $this->cols; $j++) {
			if (($this->inS($j) < 0)) {
				if ( ! isset($this->table[0][$j])) {
					$this->table[0][$j] = new rationalNum;
				}
				if ($this->table[0][$j]->num() < 0 && $this->table[0][$j]->value() < $min) {
				$k = $j;
				$min = $this->table[0][$j]->value();
				} //$this->table[0][$j]->num() < 0 && $this->table[0][$j]->value() < $min
			}
			
		} //$j = 1; $j < $this->cols; $j++
		if ($k == - 1) // c[j] >= 0 per ogni j
		return $ottima; // soluzione ottima
		// ora k e' la colonna in cui c[j] assume valore negativo max in modulo
		$h = - 1;
		$min = 10e9; // sembra un valore sufficientemente grande, no?
		// Se siamo nella prima fase del metodo delle due fasi occorre prestare
		// attenzione a scegliere elementi non appartenenti alla prima riga
		// se il termine di indice (0,1) e' =< 0
		for ($fase == 1 ? $i = 2 : $i = 1; $i < $this->rows; $i++) {
			if (isset($this->table[$i][$k])) if ($this->table[$i][$k]->num() > 0) {
				$quo = $this->table[$i][0]->value() / $this->table[$i][$k]->value();
				if ($quo < $min) {
					$h = $i;
					$min = $quo;
				} //$quo < $min
				
			} //$this->table[$i][$k]->num() > 0
			
		} //$fase == 1 ? $i = 2 : $i = 1; $i < $this->rows; $i++
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
		for ($i = 1; $i < $this->rows; $i++) {
			if ($this->table[$i][0]->num() < 0 && $this->table[$i][0]->value() < $min) {
				$h = $i;
				$min = $this->table[$i][0];
			} //$this->table[$i][0]->num() < 0 && $this->table[$i][0]->value() < $min
			
		} //$i = 1; $i < $this->rows; $i++
		if ($h == - 1) // b[i] >= 0 per ogni j
		return $ottima; // soluzione ottima
		// ora h e' la riga in cui b[i] assume valore negativo max in modulo
		$k = - 1;
		$min = 10e9; // sembra un valore sufficientemente grande, no?
		for ($j = 1; $j < $this->cols; $j++) {
			if ($this->table[$h][$j]->num() < 0) {
				$quo = $this->table[0][$j]->value() / (-$this->table[$h][$j]->value());
				if ($quo < $min) {
					$k = $j;
					$min = $quo;
				} //$quo < $min
				
			} //$this->table[$h][$j]->num() < 0
			
		} //$j = 1; $j < $this->cols; $j++
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
		return $this->table[$i][$j];
	}
	public function check_intera() /* restituisce true se la soluzione e' intera */ {
		// devono essere intere sia z
		if ($this->sol[0]->den() != 1) return false;
		// sia tutte le variabili in base
		for ($j = 0; $j < $this->cols; $j++) {
			$i = $this->in_base($j);
			if ($i != 0) {
				if (isset($this->sol[$i]->den)) if ($this->sol[$i]->den() != 1) {
					return false;
				} //$this->sol[$i]->den() != 1
				
			} //$i != 0
			
		} //$j = 0; $j < $this->cols; $j++
		return true;
	}
	public function frac($x) /* ritorna la parte frazionaria */ {
		$int = floor($x->value());
		$f = new rationalNum($int, 1);
		$f->subfrac($x, $f);
		return $f;
	}
	
	
	// generates and adds the constraint of the cutting planes method
	public function addConstraint()  {
		$f = new rationalNum;
		$frac = new rationalNum;
		$h = -1;
		$max = 0;
		// Iterate through the first column to find the largest fraction 
		for ($i = 0; $i < $this->rows; $i++) {
			$frac = $this->frac($this->table[$i][0]);
			if ($frac->value() > $max) {
				$max = $frac->value();
				$h = $i;
			}			
		}
		
		if ($h == - 1) return "Il programma &egrave; implementato male: non trova vincoli non interi";
		else $content = '<p>Riga con f<sub>i</sub> massima: <strong>' . $h . '</strong></p>';
		
		// generate and write the constraint
		$f = clone $this->frac($this->table[$h][0]);
		$this->table[$this->rows][0] = new rationalNum(-$f->num(), $f->den());
		$f = $this->frac($this->table[$h][1]);
		$this->table[$this->rows][1] = new rationalNum(-$f->num(), $f->den());
		if ($this->table[$this->rows][1]->num() != 0) {
			$f->set(-$this->table[$this->rows][1]->num(), $this->table[$this->rows][1]->den());
			//$content.= sprintf(" %s x<sub>1</sub>", $f->fractoa());
		}
		
		for ($j = 2; $j < $this->cols; $j++) {
			$f = clone $this->frac($this->table[$h][$j]);
			$this->table[$this->rows][$j] = new rationalNum(-$f->num(), $f->den());
			if ($this->table[$this->rows][$j]->num() != 0) {
				$f->set(-$this->table[$this->rows][$j]->num(), $this->table[$this->rows][$j]->den());
				//$content.= sprintf(" + %s x<sub>%d</sub>", $f->fractoa(), $j);
			} 
		} 
		$f->set(-$this->table[$this->rows][0]->num, $this->table[$this->rows][0]->den());
		//$content.= sprintf(" &gt;= %s", $f->fractoa());
		$this->table[$this->rows][$this->cols] = new rationalNum(1, 1);
		// aggiungi anche variabile di base inammissibile
		$this->S[] = $this->cols;
		$this->rows++;
		$this->cols++;
		return $content;
	}
}
?>

