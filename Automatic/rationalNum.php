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
class rationalNum {
	public $num;
	public $den;
	
	// Constructor
	public function rationalNum($num = 0, $den = 1) {
		$this->num = $num;
		$this->den = $den;
	}
	
	// Finds the greatest common divisor
	public function gcd($x, $y) {
		$a = abs($x);
		$b = abs($y);
		// ensure b is smaller than a
		if ($b > $a) {
			$tmp = $a;
			$a = $b;
			$b = $tmp;
		}
		// iterate through from highest to lowest to find the gcd which is when
		// the mod is equal to 0 or 1
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
		$s = $this->signInt($this->den);
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
	
	// returns sign multiplier of an input, negative value returns -1, positive returns 1
	private function signInt($i) {
		if ($i > 0) return 1;
		if ($i < 0) return -1;
		return 0;
	}
	
	// not used
	// returns a negative int or positive int depending on the objects number
	private function sign() {
		if ($this->num > 0) return 1;
		if ($this->num < 0) return -1;
		return 0;
	}
	
	// TODO not used
	// adds two rational fractions while maintaining rationality
	public function addfrac($x, $y) {
		$sum = new rationalNum;	
		// if one of the fractions are null or 0 then the result is simply the  
		// non zero or non null fraction
		if (!isset($x) || $x->num == 0) {
			$this->num = $y->num;
			$this->den = $y->den;
		} else if (!isset($y) || $y->num() == 0) {
			$this->num = $x->num;
			$this->den = $x->den;
		} 
		// otherwise create a common denominator and add the numerators and
		// then normalise - it uses the greatest common denominator function
		else {
			$sum->num = $x->num * $y->den + $x->den * $y->num;
			$sum->den = $x->den * $y->den;
			$sum->normalize();
			$this->num = $sum->num;
			$this->den = $sum->den;
		}
	}
	
	// subtracts one rational fraction $y from another rational fraction $x
	// while maintaining rationality
	public function subfrac($x, $y) {
		$difference = new rationalNum;
		// if one of the fractions are null or 0 then determine new value
		if (!isset($x) || $x->num == 0) {
			$this->num = - $y->num;
			$this->den = $y->den;
		} else if (!isset($y) || $y->num == 0) {
			$this->num = $x->num;
			$this->den = $x->den;
		}
		// otherwise create a common denominator and subtract the numerators
		// normalise it using gcd to make sure fraction is concise 
		else {
			$difference->num = $x->num * $y->den - $x->den * $y->num;
			$difference->den = $x->den * $y->den;
			$difference->normalize();
			$this->num = $difference->num;
			$this->den = $difference->den;
		}
	}
	
	// multiplies two rational fractions while maintaining rationality
	public function mulfrac($x, $y) {
		$product = new rationalNum;
		// if one fraction is null/indeterminate the result is 0
		if (!isset($x) || !isset($y) || $x->num == 0 || $y->num == 0) {
			$this->num = 0;
			$this->den = 1;
		} 
		// if one fraction is equal to 1 the result is the other fraction.
		// This is so gcd is already calculated
		else if ($x->value() == 1) {
			$this->num = $y->num;
			$this->den = $y->den;
		} else if ($y->value() == 1) {
			$this->num = $x->num;
			$this->den = $x->den;
		}
		// if one fraction is equal to negative 1 the result is the negative 
		// of the other fraction. This is so gcd is already calculated
		else if ($x->value() == - 1) {
			$this->num = - $y->num;
			$this->den = $y->den;
		} else if ($y->value() == - 1) {
			$this->num = - $x->num;
			$this->den = $x->den;
		} else {
		// otherwise multiply as per usual and then normalise to find the gcd
			$product->num = $x->num * $y->num;
			$product->den = $x->den * $y->den;
			$product->normalize();
			$this->num = $product->num;
			$this->den = $product->den;
		}
	}
	
	// Divides one rational fraction $x by another rational fraction $y
	// while maintaining rationality	
	public function divfrac($x, $y) {
		$quotient = new rationalNum;
		// if $x is 0 the result will be 0 
		if ($x->num == 0) {
			$this->num = 0;
			$this->den = 1;
		} 
		// prevent indeterminate results
		else if ($y->num == 0) { //TODO potential error. if x = 0 and y = 0
			printf("Division by zero");
		} 
		// invert the numerator and denominator of y and multiply and then
		// normalise to find the gcd
		else {
			$quotient->num = $x->num * $y->den;
			$quotient->den = $x->den * $y->num;
			$quotient->normalize();
			$this->num = $quotient->num;
			$this->den = $quotient->den;
		}
	}
	
	// makes the fraction negative
	public function negatefrac() {
		$this->num = - $this->num;
	}
	
	// TODO not used
	// inverts the fraction
	public function invertfrac() {
		// swap denominator and numerator values
		$tmp = $this->num;
		$this->num = $this->den;
		$this->den = $tmp;
		
		// determine if negative and 
		// TODO potential error : not sure what purpose this serves
		$s = $this->signInt($this->den);
		if ($s == 0) exit(1);
		else if ($s < 0) {
			$this->den = - $this->den;
			$this->num = - $this->num;
		}
	}
	
	//TODO not used
	public function comparefrac($x, $y) {
		$this->subfrac($x, $y);
		return $this->signInt($this->num);
	}
	
	
	// takes a string input for a fraction and parses it
	public function scanfrac($x) {
		// if the string includes "/" parse it as a normal fraction
		if (strchr($x, "/")) {
			$numDen = explode("/", $x, strlen($x));
			$this->num = $numDen[0];
			$this->den = $numDen[1];
		} 
		// if string includes "." convert to double and ..
		else if (strchr($x, ".")) { 
			$float = $x;
			settype($float, "double");
			// calculate the number of digits in the decimal and create a 
			// number has as many 0's as decimals in $x to multiply and divide
			$decimali = strlen(strchr($x, ".")) - 1;
			$this->den = pow(10, $decimali);
			$this->num = $float * $this->den;
			//	 TODO why is there no gcd?
		} 
		// If $x is whole, parse simply
		else {
			$this->num = $x;
			$this->den = 1;
		}
	}
	
	// returns string
	public function fractoa() {
		// if number is a whole number, return numerator 
		if ($this->den == 1) $str = sprintf("%d", $this->num);
		// if number is fraction print in fraction form
		else $str = sprintf("%d/%d", $this->num, $this->den);
		return $str;
	}
	
	// Getter that returns the numerator
	public function num() {
		return $this->num;
	}
	
	// Getter that returns the denominator
	public function den() {
		return $this->den;
	}
	
	// Getter that returns the value of the rational fraction
	public function value() {
		return $this->num / $this->den;
	}
	
	// Setter that sets the numerator and denominator values
	public function set($num, $den) {
		$this->num = $num;
		$this->den = $den;
	}
}
?>
