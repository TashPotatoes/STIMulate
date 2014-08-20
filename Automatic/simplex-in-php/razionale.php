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
class razionale {
	public $num;
	public $den;
	public function razionale($num = 0, $den = 1) {
		$this->num = $num;
		$this->den = $den;
	}
	public function gcd($x, $y) {
		$a = abs($x);
		$b = abs($y);
		if ($b > $a) {
			$tmp = $a;
			$a = $b;
			$b = $tmp;
		}
		for (;;) {
			if ($b == 0) return $a;
			else if ($b == 1) return $b;
			else {
				$tmp = $b;
				$b = $a % $b;
				$a = $tmp;
			}
		}
	}
	public function normalize() {
		$s = $this->sign_int($this->den);
		if ($s == 0) printf("Zero denominator.");
		else if ($s < 0) {
			$this->den = - $this->den;
			$this->num = - $this->num;
		}
		$g = $this->gcd($this->num, $this->den);
		if ($g != 1) {
			$this->num/= $g;
			$this->den/= $g;
		}
		return $this;
	}
	public function sign_int($i) {
		if ($i > 0) return 1;
		if ($i < 0) return -1;
		return 0;
	}
	public function sign() {
		if ($this->num > 0) return 1;
		if ($this->num < 0) return -1;
		return 0;
	}
	public function addfrac($x, $y) {
		$somma = new razionale;
		if (!isset($x) || $x->num == 0) {
			$this->num = $y->num;
			$this->den = $y->den;
		} else if (!isset($y) || $y->num() == 0) {
			$this->num = $x->num;
			$this->den = $x->den;
		} else {
			$somma->num = $x->num * $y->den + $x->den * $y->num;
			$somma->den = $x->den * $y->den;
			$somma->normalize();
			$this->num = $somma->num;
			$this->den = $somma->den;
		}
	}
	public function subfrac($x, $y) {
		$differenza = new razionale;
		if (!isset($x) || $x->num == 0) {
			$this->num = - $y->num;
			$this->den = $y->den;
		} else if (!isset($y) || $y->num == 0) {
			$this->num = $x->num;
			$this->den = $x->den;
		} else {
			$differenza->num = $x->num * $y->den - $x->den * $y->num;
			$differenza->den = $x->den * $y->den;
			$differenza->normalize();
			$this->num = $differenza->num;
			$this->den = $differenza->den;
		}
	}
	public function mulfrac($x, $y) {
		$prodotto = new razionale;
		if (!isset($x) || !isset($y) || $x->num == 0 || $y->num == 0) {
			$this->num = 0;
			$this->den = 1;
		} else if ($x->value() == 1) {
			$this->num = $y->num;
			$this->den = $y->den;
		} else if ($y->value() == 1) {
			$this->num = $x->num;
			$this->den = $x->den;
		} else if ($x->value() == - 1) {
			$this->num = - $y->num;
			$this->den = $y->den;
		} else if ($y->value() == - 1) {
			$this->num = - $x->num;
			$this->den = $x->den;
		} else {
			$prodotto->num = $x->num * $y->num;
			$prodotto->den = $x->den * $y->den;
			$prodotto->normalize();
			$this->num = $prodotto->num;
			$this->den = $prodotto->den;
		}
	}
	public function divfrac($x, $y) {
		$quoziente = new razionale;
		if ($x->num == 0) {
			$this->num = 0;
			$this->den = 1;
		} else if ($y->num == 0) {
			printf("Division by zero");
		} else {
			// echo "\n<br />divido " . $x->fractoa() . " per " . $y->fractoa();
			$quoziente->num = $x->num * $y->den;
			$quoziente->den = $x->den * $y->num;
			$quoziente->normalize();
			$this->num = $quoziente->num;
			$this->den = $quoziente->den;
			// echo " e ottengo " . $this->fractoa() . "<br />\n";
			
		}
	}
	public function negatefrac() {
		$this->num = - $this->num;
	}
	public function invertfrac() {
		$tmp = $this->num;
		$this->num = $this->den;
		$this->den = $tmp;
		$s = $this->sign_int($this->den);
		if ($s == 0) exit(1);
		else if ($s < 0) {
			$this->den = - $this->den;
			$this->num = - $this->num;
		}
	}
	public function comparefrac($x, $y) {
		$this->subfrac($x, $y);
		return $this->sign_int($this->num);
	}
	public function scanfrac($x) {
		// Se c'e' uno slash e' espresso direttamente come frazione
		if (strchr($x, "/")) {
			$num_den = explode("/", $x, strlen($x));
			$this->num = $num_den[0];
			$this->den = $num_den[1];
		} else if (strchr($x, ".")) { // e' espresso come decimale
			// facciamo fare il lavoro al PHP
			$float = $x;
			settype($float, "double");
			// quante cifre dopo la virgola?
			$decimali = strlen(strchr($x, ".")) - 1;
			$this->den = pow(10, $decimali);
			$this->num = $float * $this->den;
		} else { // e' un intero
			$this->num = $x;
			$this->den = 1;
		}
	}
	public function fractoa() {
		if ($this->den == 1) $str = sprintf("%d", $this->num);
		else $str = sprintf("%d/%d", $this->num, $this->den);
		return $str;
	}
	public function num() {
		return $this->num;
	}
	public function den() {
		return $this->den;
	}
	public function value() {
		return $this->num / $this->den;
	}
	public function set($num, $den) {
		$this->num = $num;
		$this->den = $den;
	}
}
?>
