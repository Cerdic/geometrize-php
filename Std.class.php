<?php

// Generated by Haxe 3.4.7
class Std {
	public function __construct(){}
	static function string($s) {
		return _hx_string_rec($s, "");
	}
	static function int($x) {
		$i = fmod($x, -2147483648) & -1;
		if($i & -2147483648) {
			$i = -((~$i & -1) + 1);
		}
		return $i;
	}
	static function random($x) {
		if($x <= 0) {
			return 0;
		} else {
			return mt_rand(0, $x - 1);
		}
	}
	function __toString() { return 'Std'; }
}