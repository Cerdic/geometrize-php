<?php

// Generated by Haxe 3.4.7
class geometrize_Util {
	public function __construct(){}
	static function getAverageImageColor($image, $alpha = null) {
		if($alpha === null) {
			$alpha = 255;
		}
		if(!($image !== null)) {
			throw new HException("FAIL: image != null");
		}
		$totalRed = 0;
		$totalGreen = 0;
		$totalBlue = 0;
		{
			$_g1 = 0;
			$_g = $image->width;
			while($_g1 < $_g) {
				$_g1 = $_g1 + 1;
				$x = $_g1 - 1;
				{
					$_g3 = 0;
					$_g2 = $image->height;
					while($_g3 < $_g2) {
						$_g3 = $_g3 + 1;
						$y = $_g3 - 1;
						$pixel = $image->data[$image->width * $y + $x];
						$totalRed = $totalRed + ($pixel >> 24 & 255);
						$totalGreen = $totalGreen + ($pixel >> 16 & 255);
						$totalBlue = $totalBlue + ($pixel >> 8 & 255);
						unset($y,$pixel);
					}
					unset($_g3,$_g2);
				}
				unset($x);
			}
		}
		$size = $image->width * $image->height;
		$red = Std::int($totalRed / $size);
		$green = Std::int($totalGreen / $size);
		$blue = Std::int($totalBlue / $size);
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$tmp = null;
		if($red < 0) {
			$tmp = 0;
		} else {
			if($red > 255) {
				$tmp = 255;
			} else {
				$tmp = $red;
			}
		}
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$tmp1 = null;
		if($green < 0) {
			$tmp1 = 0;
		} else {
			if($green > 255) {
				$tmp1 = 255;
			} else {
				$tmp1 = $green;
			}
		}
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$tmp2 = null;
		if($blue < 0) {
			$tmp2 = 0;
		} else {
			if($blue > 255) {
				$tmp2 = 255;
			} else {
				$tmp2 = $blue;
			}
		}
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$tmp3 = null;
		if($alpha < 0) {
			$tmp3 = 0;
		} else {
			if($alpha > 255) {
				$tmp3 = 255;
			} else {
				$tmp3 = $alpha;
			}
		}
		return ($tmp << 24) + ($tmp1 << 16) + ($tmp2 << 8) + $tmp3;
	}
	static function clamp($value, $min, $max) {
		if(!($min <= $max)) {
			throw new HException("FAIL: min <= max");
		}
		if($value < $min) {
			return $min;
		} else {
			if($value > $max) {
				return $max;
			} else {
				return $value;
			}
		}
	}
	static function min($first, $second) {
		if($first < $second) {
			return $first;
		} else {
			return $second;
		}
	}
	static function max($first, $second) {
		if($first > $second) {
			return $first;
		} else {
			return $second;
		}
	}
	static function toRadians($degrees) {
		return $degrees * Math::$PI / 180;
	}
	static function toDegrees($radians) {
		return $radians * 180 / Math::$PI;
	}
	static function random($lower, $upper) {
		if(!($lower <= $upper)) {
			throw new HException("FAIL: lower <= upper");
		}
		return $lower + Math::floor(($upper - $lower + 1) * Math::random());
	}
	static function randomArrayItem($a) {
		$tmp = null;
		if($a !== null) {
			$tmp = $a->length > 0;
		} else {
			$tmp = false;
		}
		if(!$tmp) {
			throw new HException("FAIL: a != null && a.length > 0");
		}
		$upper = $a->length - 1;
		if(!(0 <= $upper)) {
			throw new HException("FAIL: lower <= upper");
		}
		return $a[Math::floor(($upper + 1) * Math::random())];
	}
	static function minMaxElements($a) {
		$tmp = null;
		if($a !== null) {
			$tmp = $a->length === 0;
		} else {
			$tmp = true;
		}
		if($tmp) {
			return _hx_anonymous(array("x" => 0, "y" => 0));
		}
		$min = $a[0];
		$max = $a[0];
		{
			$_g = 0;
			while($_g < $a->length) {
				$value = $a[$_g];
				$_g = $_g + 1;
				if($min > $value) {
					$min = $value;
				}
				if($max < $value) {
					$max = $value;
				}
				unset($value);
			}
		}
		return _hx_anonymous(array("x" => $min, "y" => $max));
	}
	static function abs($value) {
		if($value < 0) {
			return -$value;
		}
		return $value;
	}
	function __toString() { return 'geometrize.Util'; }
}