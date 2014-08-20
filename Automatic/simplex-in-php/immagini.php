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
class gf_cartesiano {
	// dimensioni immagine totale
	public $_x_size_;
	public $_y_size_;
	// coordinate origine assi
	public $_x0_;
	public $_y0_;
	// dimensioni asse x
	public $_xi_;
	public $_xf_;
	public $_x_axis_;
	// dimensioni asse y
	public $_yi_;
	public $_yf_;
	public $_y_axis_;
	// l'immagine
	public $image;
	// qualche colore
	public $white;
	public $black;
	public $red;
	public $blue;
	public $yellow;
	// uso un po' strano perche' non settata in modo valido dal costruttore
	public $scale;
	public function __construct(&$x_axis, &$y_axis)
	// costruttore
	{
		// settta le dimensioni dell'immagine
		$this->_x_size_ = 480; // 480;
		$this->_y_size_ = 480; // 480;
		// coordinate origine assi
		$this->_x0_ = 30;
		$this->_y0_ = $this->_y_size_ - 30;
		// dimensioni asse x
		$this->_xi_ = 10;
		$this->_xf_ = $this->_x_size_ - $this->_xi_;
		$this->_x_axis_ = $this->_xf_ - $this->_x0_ - 10;
		$x_axis = $this->_x_axis_;
		// dimensioni asse y
		$this->_yi_ = 10;
		$this->_yf_ = $this->_y_size_ - $this->_yi_;
		$this->_y_axis_ = $this->_yf_ - $this->_y0_ - 10;
		$y_axis = $this->_y_axis_;
		// crea lo sfondo
		$this->image = imagecreatetruecolor($this->_x_size_, $this->_y_size_);
		// alloca i colori
		$this->white = ImageColorAllocate($this->image, 255, 255, 255);
		$this->black = ImageColorAllocate($this->image, 0, 0, 0);
		//$this->red = ImageColorAllocate($this->image, 255, 0, 0);
		$this->red = ImageColorAllocate($this->image, 127, 0, 0);
		$this->green = ImageColorAllocate($this->image, 0, 255, 0);
		$this->blue = ImageColorAllocate($this->image, 0, 0, 255);
		$this->magenta = ImageColorAllocate($this->image, 255, 0, 255);
		$this->yellow = ImageColorAllocate($this->image, 255, 255, 0);
		$this->darkgreen = ImageColorAllocate($this->image, 0, 100, 0);
		//$this->darkorange = ImageColorAllocate($this->image, 255, 127, 0);
		$this->darkorange = ImageColorAllocate($this->image, 255, 63, 63);
		// assegna un valore a caso a scale
		$this->scale = 1;
		// ritorna il puntatore all'immagine
		// return $this->image;
	}
	public function fq_col($col)
	// colora il primo quadrante
	{
		$tile = imageCreateFromPNG('images/regione_amm.png');
		if ($tile == false) { die ('Unable to open image'); }
		imageSetTile ($this->image, $tile);
		imagefilledrectangle($this->image,  0,  0, $this->_x_size_, $this->_y_size_, $this->white);
		imagefilledrectangle($this->image, 30, 20, $this->_x_size_ -20, $this->_x_size_ - 30, IMG_COLOR_TILED);
	}
	public function assi($vertici, $scale)
	// disegna gli assi e scrive i valori
	{
		// disegna asse x
		imageline($this->image, $this->_xi_, $this->_y0_, $this->_xf_, $this->_y0_, $this->black);
		// disegna frecce asse x
		imageline($this->image, $this->_xf_ - 10, $this->_y0_ - 10, $this->_xf_, $this->_y0_, $this->black);
		imageline($this->image, $this->_xf_ - 10, $this->_y0_ + 10, $this->_xf_, $this->_y0_, $this->black);
		// scrivi O nell'origine
		imagechar($this->image, 3, $this->_x0_ - 25, $this->_y0_ + 10, "O", $this->black);
		// disegna lettere asse x e stanghette
		imagechar($this->image, 3, $this->_xf_ - 10, $this->_y0_ + 10, "x", $this->black);
		imagechar($this->image, 2, $this->_xf_, $this->_y0_ + 15, "1", $this->black);
		for ($i = 0; $i < count($vertici); $i+= 2) if ($vertici[$i] > 0) {
			$x = $this->map_x($vertici[$i], $scale);
			imageline($this->image, $x, $this->_y0_, $x, $this->_y0_ + 5, $this->black);
			if (abs(floor($vertici[$i]) - $vertici[$i]) <= 0.00001) $lettere = sprintf("%d", $vertici[$i]);
			else $lettere = sprintf("%.2f", $vertici[$i]);
			imagestring($this->image, 3, $x - 10, $this->_y0_ + 10, $lettere, $this->black);
		}
		// disegna asse y
		imageline($this->image, $this->_x0_, $this->_yi_, $this->_x0_, $this->_yf_, $this->black);
		// disegna frecce asse y
		imageline($this->image, $this->_x0_ - 10, $this->_yi_ + 10, $this->_x0_, $this->_yi_, $this->black);
		imageline($this->image, $this->_x0_ + 10, $this->_yi_ + 10, $this->_x0_, $this->_yi_, $this->black);
		// disegna lettere asse y
		imagechar($this->image, 3, $this->_x0_ - 25, $this->_yi_ + 5, "x", $this->black);
		imagechar($this->image, 2, $this->_x0_ - 15, $this->_yi_ + 10, "2", $this->black);
		for ($i = 1; $i < count($vertici); $i+= 2) if ($vertici[$i] > 0) {
			$y = $this->map_y($vertici[$i], $scale);
			imageline($this->image, $this->_x0_ - 5, $y, $this->_x0_, $y, $this->black);
			if (abs(floor($vertici[$i]) - $vertici[$i]) <= 0.00001) $lettere = sprintf("%d", $vertici[$i]);
			else $lettere = sprintf("%.2f", $vertici[$i]);
			imagestring($this->image, 3, $this->_x0_ - 30, $y, $lettere, $this->black);
		}
	}
	public function map_x($vx, $scale)
	/* ritorna il valore della coordinata x sull'immagine corrispondente al punto
	 di coordinata vx nell'asse cartesiano, fissato il parametro di scala */ {
		return $this->_x0_ + round($scale * $vx);
	}
	public function map_y($vy, $scale)
	/* ritorna il valore della coordinata y sull'immagine corrispondente al punto
	 di coordinata vy nell'asse cartesiano, fissato il parametro di scala */ {
		return $this->_y0_ - round($scale * $vy);
	}
	public function ruota($x, $y, &$xr, &$yr, $alpha)
	// ruota (x, y)-(0 ,0) di un angolo alpha
	{
		$xr = $x * cos($alpha) - $y * sin($alpha);
		$yr = $y * cos($alpha) + $x * sin($alpha);
	}
	public function line($scale, $vxi, $vyi, $vxf, $vyf, $col)
	/* disegna il segmento corrispondente a quello PQ fra P=(vxi, vyi) e q=(vxf, vyf) */ {
		// calcolo nuove coordinate
		$xi = $this->map_x($vxi, $scale);
		$xf = $this->map_x($vxf, $scale);
		$yi = $this->map_y($vyi, $scale);
		$yf = $this->map_y($vyf, $scale);
		// disegna il segmento
		imageline($this->image, $xi, $yi, $xf, $yf, $col);
		imageline($this->image, $xi-1, $yi, $xf-1, $yf, $col);
		imageline($this->image, $xi+1, $yi, $xf+1, $yf, $col);
		imageline($this->image, $xi, $yi-1, $xf, $yf-1, $col);
		imageline($this->image, $xi, $yi+1, $xf, $yf+1, $col);
	}
	public function freccia($scale, $vxi, $vyi, $vxf, $vyf, $col)
	/* disegna la freccia P-Q fra P=(vxi, vyi) e q=(vxf, vyf) con ampiezza 45 gradi*/ {
		/*             R             R __. P
		         45� \                 /\  S
		  Q ----------> P             / angolo di cui calcolo sin e cos
		         45� /       _______ /)______________ 
		            S               Q               x
		*/
		$xr = 0; $yr = 0; $xs = 0; $ys = 0;
		// calcolo nuove coordinate
		$xi = $this->map_x($vxi, $scale);
		$xf = $this->map_x($vxf, $scale);
		$yi = $this->map_y($vyi, $scale);
		$yf = $this->map_y($vyf, $scale);
		// trasliamo P-Q nell'origine per fare i conti
		$x = $xf - $xi;
		$y = - ($yf - $yi);
		$modulo = sqrt($x * $x + $y * $y);
		// calcola seno e coseno dell'angolo fra il segmento e l'asse x
		if (!$modulo) return 0;
		$sin_ = $y / $modulo;
		$cos_ = $x / $modulo;
		// calcola gli estremi dei segmenti delle frecce
		// r rotazione di 45deg del vettore di modulo 10 e direzione di Q-P
		$this->ruota(-10 * $cos_, -10 * $sin_, $xr, $yr, M_PI / 6);
		$xr+= $xf;
		$yr = $yf - $yr;
		// s rotazione di -45� del vettore di modulo 10 e direzione di Q-P
		$this->ruota(-10 * $cos_, -10 * $sin_, $xs, $ys, 11 * M_PI / 6);
		$xs+= $xf;
		$ys = $yf - $ys;
		// disegna il segmento
		imageline($this->image, $xi, $yi, $xf, $yf, $col);
		imageline($this->image, $xi-1, $yi, $xf-1, $yf, $col);
		imageline($this->image, $xi+1, $yi, $xf+1, $yf, $col);
		imageline($this->image, $xi, $yi-1, $xf, $yf-1, $col);
		imageline($this->image, $xi, $yi+1, $xf+1, $yf, $col);
		imagefilledpolygon($this->image, array($xr, $yr, $xs, $ys, $xf, $yf), 3, $col);
		//imageline($this->image,$xr,$yr,$xf,$yf,$col);
		//imageline($this->image,$xs,$ys,$xf,$yf,$col);
		
	}
	public function cerchio($scale, $vx0, $vy0, $l, $col)
	/* disegna una circonferenza di raggio r pixel e centro P=(vx, vy) */ {
		$x0 = $this->map_x($vx0, $scale);
		$y0 = $this->map_y($vy0, $scale);
		$l2 = round($l / 2);
		//imagefilledellipse($this->image,$x0,$y0,$r,$r,$col); // non gestita da vecchie gd
		//imagefilledrectangle($this->image, $x0 - $l2, $y0 - $l2, $x0 + $l2, $y0 + $l2, $col);
		imagefilledrectangle($this->image, $x0 - 3*$l2/2, $y0 - 3*$l2/2, $x0 + 3*$l2/2, $y0 + 3*$l2/2, $col);
	}
	public function ping($name)
	// scrive l'immagine in formato PNG nel file di nome $name
	{
		imagepng($this->image, $name);
	}
	public function plotta_disequazione($ax, $ay, $b, $lge, $scale, $col)
	/* disegna i segmenti corrispondenti alla dis/equazione immesssa e cancella
	 la parte che non verifica la disequazione usando il colore $col */ { // ax x + ay y lge b
		// costanti
		$max = $this->_x_axis_ / $scale;
		$p0x = 0;
		$p0y = 0; // (sx,basso)
		$p1x = $max;
		$p1y = 0; // (dx,basso)
		$p2x = $max;
		$p2y = $max; // (dx, alto)
		$p3x = 0;
		$p3y = $max; // (sx, alto)
		$p4x = 0;
		$p4y = 0;
		
		if ($ax == 0) { // ay y = b
			if ($ay == 0) return 0;
			$vx0 = 0;
			$vx1 = $max;
			$vy0 = $b / $ay;
			$vy1 = $vy0;
			// se si tratta di un'equazione, scrivi l'immagine e ritorna
			if (!strcmp($lge, "=")) {
				$this->line($scale, $vx0, $vy0, $vx1, $vy1, $col);
				return 0;
			}
			// si tratta di una disequazione ay y <> b
			if (strstr($lge, ">")) if ($ay > 0) $cancella_sotto = true;
			else $cancella_sotto = false;
			else if ($ay > 0) $cancella_sotto = false;
			else $cancella_sotto = true;
			if ($cancella_sotto) {
				if ($vy0 > 0) imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($vy0, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			} else if ($vy0 > 0) imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y($vy0, $scale), $col);
			else imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			return 0;
		}
		if ($ay) {
			$m = - $ax / $ay;
			$q = $b / $ay;
		} else { // ax x = b
			$vx0 = $b / $ax;
			$vx1 = $vx0;
			$vy0 = 0;
			$vy1 = $max;
			// se si tratta di un'equazione, scrivi l'immagine e ritorna
			if (!strcmp($lge, "=")) {
				$this->line($scale, $vx0, $vy0, $vx1, $vy1, $col);
				return 0;
			}
			// si tratta di una disequazione
			if (strstr($lge, ">")) if ($ax > 0) $cancella_sx = true;
			else $cancella_sx = false;
			else if ($ax > 0) $cancella_sx = false;
			else $cancella_sx = true;
			if ($cancella_sx) {
				if ($vx0 > 0) imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($vx0, $scale), $this->map_y(0, $scale), $col);
			} else if ($vx0 > 0) imagefilledrectangle($this->image, $this->map_x($vx0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			else imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			return 0;
		}
		// setta i vertici della retta ax x + ay y = b
		if ($m >= 0) if ($q >= 0) {
			$vx0 = 0;
			$vy0 = $q;
			if (($vy1 = $m * $max + $q) < $max) {
				$vx1 = $max;
			} else {
				$vy1 = $max;
				$vx1 = ($max - $q) / $m;
			}
		} else {
			$vx0 = $b / $ax;
			$vy0 = 0;
			if (($vy1 = $m * $max + $q) < $max) {
				$vx1 = $max;
			} else {
				$vy1 = $max;
				$vx1 = ($max - $q) / $m;
			}
		} else { // m<0
			if ($q >= 0) {
				$vx0 = 0;
				$vy0 = $q;
				if (($vy1 = $m * $max + $q) > 0) {
					$vx1 = $max;
				} else {
					$vy1 = 0;
					$vx1 = $b / $ax;
				}
			}
		}
		// se si tratta di un'equazione, scrivi l'immagine e ritorna
		if (!strcmp($lge, "=")) {
			$this->line($scale, $vx0, $vy0, $vx1, $vy1, $col);
			return 0;
		}
		// si tratta di una disequazione
		if (strstr($lge, ">")) if ($ay > 0) $cancella_sotto = true;
		else $cancella_sotto = false;
		else if ($ay > 0) $cancella_sotto = false;
		else $cancella_sotto = true;
		//	echo "<br /> y = " . $m . "x + " . $q . "<br />\n";
		//	echo "<br /> cancella_sotto = " . (bool)(isset($cancella_sotto) && $cancella_sotto) . "<br />\n";
		//	echo "<br /> isset(cancella_sotto) = " . isset($cancella_sotto) . "<br />\n";
		if ($m >= 0) // m >= 0
		if ($q >= 0) { // m, q >= 0
			if (!$cancella_sotto) { // m, q >= 0: colora il poligono piu' alto
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p3x, $p3y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p3x, $p3y);
			} else { // m, q >= 0: colora il poligono in basso
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y, $p0x, $p0y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p1x, $p1y, $p0x, $p0y);
			}
		} else { // m>=0, q<0
			if (!$cancella_sotto) { // m>=0, q<0: alto
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p3x, $p3y, $p0x, $p0y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p3x, $p3y, $p0x, $p0y);
			} else { // m>=0, q<0: basso
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p1x, $p1y);
			}
		} else
		// m < 0
		if ($q >= 0) { // m < 0, q >= 0
			if (!$cancella_sotto) { // m < 0, q >= 0: alto
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p3x, $p3y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y);
			} else { // m < 0, q >= 0: basso
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y, $p0x, $p0y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p0x, $p0y);
			}
		} else { // m, q < 0
			if (!$cancella_sotto) { // m>=0, q<0: alto
				if ($vx1 == $max) $array = array($p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y);
			}
		}
		if (isset($array)) {
			for ($i = 0; $i < count($array);) {
				$array1[$i] = $this->map_x($array[$i], $scale);
				$array1[$i + 1] = $this->map_y($array[$i + 1], $scale);
				//imagearc($this->image, $array1[$i], $array1[$i+1],1,1,0,360,$this->blue);
				$i+= 2;
			}
			imagefilledpolygon($this->image, $array1, count($array1) / 2, $col);
			unset($array);
			unset($array1);
		}
	}
}
class grafico extends gf_cartesiano {
	public $basename;
	public $middlename;
	public $extension = ".png";
	public $vertici;
	public $grad_x;
	public $grad_y;
	public function __construct($_a, $_b, $_c, $lge, $cambia_segno_gradiente, $name) {
		$asse_x = 0;
		$asse_y = 0;
		parent::__construct($asse_x, $asse_y);
		/* sostituisci in $a, $b, $c i valori agli oggetti frazione */
		for ($i = 1; $i < count($_b) + 1; $i++) {
			$b[$i] = $_b[$i]->value();
			for ($j = 1; $j < 3; $j++) $a[$i][$j] = $_a[$i][$j]->value();
		}
		for ($j = 1; $j < 3; $j++) $c[$j] = $_c[$j]->value();
		if ($cambia_segno_gradiente) {
			$this->grad_x = - $c[1];
			$this->grad_y = - $c[2];
		} else {
			$this->grad_x = $c[1];
			$this->grad_y = $c[2];
		}
		$this->basename = $name;
		// $this->image = $this->gf_cartesiano ($asse_x, $asse_y);
		$this->fq_col($this->yellow);
		$vertici = array();
		$this->trova_vertici($a, $b, $vertici);
		$max = $this->cerca_max($vertici) * 1.1;
		$this->scale = $asse_x / $max;
		for ($i = 1; $i < count($b) + 1; $i++) {
			$this->plotta_disequazione($a[$i][1], $a[$i][2], $b[$i], $lge[$i], $this->scale, $this->white);
			//		$this->ping($name . "___"  $i);
			
		}
		for ($i = 1; $i < count($b) + 1; $i++) $this->plotta_disequazione($a[$i][1], $a[$i][2], $b[$i], "=", $this->scale, $this->blue);
		$this->gradiente();
		$this->assi($vertici, $this->scale);
		$name = $this->basename . $this->extension;
		$this->ping($name);
		return $this->image;
	}
	public function gradiente() {
		$this->freccia($this->scale, 0, 0, $this->grad_x, $this->grad_y, $this->magenta);
	}
	public function passo($xi, $yi, $xf, $yf, $passo, &$name) {
		$name = $this->basename . $passo;
		/* la perpendicolare al gradiente passante per xf, yf */
		$this->plotta_disequazione($this->grad_x, $this->grad_y, ($xf * $this->grad_x + $yf * $this->grad_y), "=", $this->scale, $this->darkgreen);
		$this->cerchio($this->scale, $xf, $yf, 6, $this->red);
		$this->freccia($this->scale, $xi, $yi, $xf, $yf, $this->darkorange);
		
		$this->ping($name . ".png");
		$this->gradiente();
		$this->cerchio($this->scale, $xf, $yf, 6, $this->red);
	}
	public function cerca_max(&$vertici) {
		$max = $vertici[0];
		for ($i = 1; $i < count($vertici); $i++) if ($vertici[$i] > $max) $max = $vertici[$i];
		// $max e' il max fra le intercette e i valori di x e di y dei vertici
		return $max;
	}
	public function trova_vertici($a, $b, &$vertici) {
		// numero di equazioni
		$m = count($b);
		$vertici = array_fill(0, ($m * $m + 3 * $m) / 2 + 1, 0);
		// dove incidono le rette
		for ($k = 0, $i = 1; $i < $m + 1; $i++) for ($j = $i + 1; $j < $m + 1; $j++) {
			// le x
			if ($a[$i][1] * $a[$j][2] != $a[$j][1] * $a[$i][2]) $vertici[$k] = ($a[$j][2] * $b[$i] - $a[$i][2] * $b[$j]) / ($a[$i][1] * $a[$j][2] - $a[$j][1] * $a[$i][2]);
			else $vertici[$k] = 0;
			$k++;
			// le y
			if ($a[$i][2] != 0) $vertici[$k] = ($b[$i] - $a[$i][1] * $vertici[$k - 1]) / $a[$i][2];
			else $vertici[$k] = 0;
			$k++;
			// echo "$i &cap; $j = (" . $vertici[$k-2] . ", " . $vertici[$k - 1] . ") <br />\n";
			
		}
		// aggiungiamo intersezioni con gli assi
		for ($i = 1; $i < $m + 1; $i++) {
			if (!isset($a[$i][1]) || $a[$i][1] != 0) {
				$vertici[$k++] = $b[$i] / $a[$i][1];
				$vertici[$k++] = 0;
			} //else {
			//	$vertici[$k++]=0;
			// echo "$i &cap; _0_ = (" . $vertici[$k-2] . ", " . $vertici[$k - 1] . ") <br />\n";
			if (!isset($a[$i][2]) || $a[$i][2] != 0) {
				$vertici[$k++] = 0;
				$vertici[$k++] = $b[$i] / $a[$i][2];
			} // else
			//	$vertici[$k++]=0;
			// echo "_0_ &cap; $i = (" . $vertici[$k-2] . ", " . $vertici[$k - 1] . ") <br />\n";
			
		}
	}
}
?>
