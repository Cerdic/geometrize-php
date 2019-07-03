<?php

// Generated by Haxe 3.4.7
class geometrize_shape_RotatedRectangle implements geometrize_shape_Shape{
	public function __construct($xBound, $yBound) {
		if(!php_Boot::$skip_constructor) {
		$this->x1 = Std::random($xBound);
		$this->y1 = Std::random($yBound);
		$value = $this->x1;
		$value1 = $value + Std::random(32) + 1;
		if(!(0 <= $xBound)) {
			throw new HException("FAIL: min <= max");
		}
		$tmp = null;
		if($value1 < 0) {
			$tmp = 0;
		} else {
			if($value1 > $xBound) {
				$tmp = $xBound;
			} else {
				$tmp = $value1;
			}
		}
		$this->x2 = $tmp;
		$value2 = $this->y1;
		$value3 = $value2 + Std::random(32) + 1;
		if(!(0 <= $yBound)) {
			throw new HException("FAIL: min <= max");
		}
		$tmp1 = null;
		if($value3 < 0) {
			$tmp1 = 0;
		} else {
			if($value3 > $yBound) {
				$tmp1 = $yBound;
			} else {
				$tmp1 = $value3;
			}
		}
		$this->y2 = $tmp1;
		if(!true) {
			throw new HException("FAIL: lower <= upper");
		}
		$this->angle = Math::floor(361 * Math::random());
		$this->xBound = $xBound;
		$this->yBound = $yBound;
	}}
	public $x1;
	public $y1;
	public $x2;
	public $y2;
	public $angle;
	public $xBound;
	public $yBound;
	public function rasterize() {
		$first = $this->x1;
		$second = $this->x2;
		$xm1 = null;
		if($first < $second) {
			$xm1 = $first;
		} else {
			$xm1 = $second;
		}
		$first1 = $this->x1;
		$second1 = $this->x2;
		$xm2 = null;
		if($first1 > $second1) {
			$xm2 = $first1;
		} else {
			$xm2 = $second1;
		}
		$first2 = $this->y1;
		$second2 = $this->y2;
		$ym1 = null;
		if($first2 < $second2) {
			$ym1 = $first2;
		} else {
			$ym1 = $second2;
		}
		$first3 = $this->y1;
		$second3 = $this->y2;
		$ym2 = null;
		if($first3 > $second3) {
			$ym2 = $first3;
		} else {
			$ym2 = $second3;
		}
		$cx = Std::int(($xm1 + $xm2) / 2);
		$cy = Std::int(($ym1 + $ym2) / 2);
		$ox1 = $xm1 - $cx;
		$ox2 = $xm2 - $cx;
		$oy1 = $ym1 - $cy;
		$oy2 = $ym2 - $cy;
		$rads = $this->angle * Math::$PI / 180.0;
		$c = Math::cos($rads);
		$s = Math::sin($rads);
		$ulx = Std::int($ox1 * $c - $oy1 * $s + $cx);
		$uly = Std::int($ox1 * $s + $oy1 * $c + $cy);
		$blx = Std::int($ox1 * $c - $oy2 * $s + $cx);
		$bly = Std::int($ox1 * $s + $oy2 * $c + $cy);
		$urx = Std::int($ox2 * $c - $oy1 * $s + $cx);
		$ury = Std::int($ox2 * $s + $oy1 * $c + $cy);
		$brx = Std::int($ox2 * $c - $oy2 * $s + $cx);
		$bry = Std::int($ox2 * $s + $oy2 * $c + $cy);
		$tmp = geometrize_rasterizer_Rasterizer::scanlinesForPolygon((new _hx_array(array(_hx_anonymous(array("x" => $ulx, "y" => $uly)), _hx_anonymous(array("x" => $urx, "y" => $ury)), _hx_anonymous(array("x" => $brx, "y" => $bry)), _hx_anonymous(array("x" => $blx, "y" => $bly))))));
		return geometrize_rasterizer_Scanline::trim($tmp, $this->xBound, $this->yBound);
	}
	public function mutate() {
		$r = Std::random(3);
		switch($r) {
		case 0:{
			$value = $this->x1;
			if(!true) {
				throw new HException("FAIL: lower <= upper");
			}
			$value1 = $value + (-16 + Math::floor(33 * Math::random()));
			$max = $this->xBound - 1;
			if(!(0 <= $max)) {
				throw new HException("FAIL: min <= max");
			}
			$tmp = null;
			if($value1 < 0) {
				$tmp = 0;
			} else {
				if($value1 > $max) {
					$tmp = $max;
				} else {
					$tmp = $value1;
				}
			}
			$this->x1 = $tmp;
			$value2 = $this->y1;
			if(!true) {
				throw new HException("FAIL: lower <= upper");
			}
			$value3 = $value2 + (-16 + Math::floor(33 * Math::random()));
			$max1 = $this->yBound - 1;
			if(!(0 <= $max1)) {
				throw new HException("FAIL: min <= max");
			}
			$tmp1 = null;
			if($value3 < 0) {
				$tmp1 = 0;
			} else {
				if($value3 > $max1) {
					$tmp1 = $max1;
				} else {
					$tmp1 = $value3;
				}
			}
			$this->y1 = $tmp1;
		}break;
		case 1:{
			$value4 = $this->x2;
			if(!true) {
				throw new HException("FAIL: lower <= upper");
			}
			$value5 = $value4 + (-16 + Math::floor(33 * Math::random()));
			$max2 = $this->xBound - 1;
			if(!(0 <= $max2)) {
				throw new HException("FAIL: min <= max");
			}
			$tmp2 = null;
			if($value5 < 0) {
				$tmp2 = 0;
			} else {
				if($value5 > $max2) {
					$tmp2 = $max2;
				} else {
					$tmp2 = $value5;
				}
			}
			$this->x2 = $tmp2;
			$value6 = $this->y2;
			if(!true) {
				throw new HException("FAIL: lower <= upper");
			}
			$value7 = $value6 + (-16 + Math::floor(33 * Math::random()));
			$max3 = $this->yBound - 1;
			if(!(0 <= $max3)) {
				throw new HException("FAIL: min <= max");
			}
			$tmp3 = null;
			if($value7 < 0) {
				$tmp3 = 0;
			} else {
				if($value7 > $max3) {
					$tmp3 = $max3;
				} else {
					$tmp3 = $value7;
				}
			}
			$this->y2 = $tmp3;
		}break;
		case 2:{
			$value8 = $this->angle;
			if(!true) {
				throw new HException("FAIL: lower <= upper");
			}
			$value9 = $value8 + (-4 + Math::floor(9 * Math::random()));
			if(!true) {
				throw new HException("FAIL: min <= max");
			}
			$tmp4 = null;
			if($value9 < 0) {
				$tmp4 = 0;
			} else {
				if($value9 > 360) {
					$tmp4 = 360;
				} else {
					$tmp4 = $value9;
				}
			}
			$this->angle = $tmp4;
		}break;
		}
	}
	public function hclone() {
		$rectangle = new geometrize_shape_RotatedRectangle($this->xBound, $this->yBound);
		$rectangle->x1 = $this->x1;
		$rectangle->y1 = $this->y1;
		$rectangle->x2 = $this->x2;
		$rectangle->y2 = $this->y2;
		$rectangle->angle = $this->angle;
		return $rectangle;
	}
	public function getType() {
		return 1;
	}
	public function getRawShapeData() {
		$first = $this->x1;
		$second = $this->x2;
		$tmp = null;
		if($first < $second) {
			$tmp = $first;
		} else {
			$tmp = $second;
		}
		$first1 = $this->y1;
		$second1 = $this->y2;
		$tmp1 = null;
		if($first1 < $second1) {
			$tmp1 = $first1;
		} else {
			$tmp1 = $second1;
		}
		$first2 = $this->x1;
		$second2 = $this->x2;
		$tmp2 = null;
		if($first2 > $second2) {
			$tmp2 = $first2;
		} else {
			$tmp2 = $second2;
		}
		$first3 = $this->y1;
		$second3 = $this->y2;
		$tmp3 = null;
		if($first3 > $second3) {
			$tmp3 = $first3;
		} else {
			$tmp3 = $second3;
		}
		return (new _hx_array(array($tmp, $tmp1, $tmp2, $tmp3, $this->angle)));
	}
	public function getSvgShapeData() {
		$first = $this->x1;
		$second = $this->x2;
		$xm1 = null;
		if($first < $second) {
			$xm1 = $first;
		} else {
			$xm1 = $second;
		}
		$first1 = $this->x1;
		$second1 = $this->x2;
		$xm2 = null;
		if($first1 > $second1) {
			$xm2 = $first1;
		} else {
			$xm2 = $second1;
		}
		$first2 = $this->y1;
		$second2 = $this->y2;
		$ym1 = null;
		if($first2 < $second2) {
			$ym1 = $first2;
		} else {
			$ym1 = $second2;
		}
		$first3 = $this->y1;
		$second3 = $this->y2;
		$ym2 = null;
		if($first3 > $second3) {
			$ym2 = $first3;
		} else {
			$ym2 = $second3;
		}
		$cx = Std::int(($xm1 + $xm2) / 2);
		$cy = Std::int(($ym1 + $ym2) / 2);
		$ox1 = $xm1 - $cx;
		$ox2 = $xm2 - $cx;
		$oy1 = $ym1 - $cy;
		$oy2 = $ym2 - $cy;
		$rads = $this->angle * Math::$PI / 180.0;
		$c = Math::cos($rads);
		$s = Math::sin($rads);
		$ulx = Std::int($ox1 * $c - $oy1 * $s + $cx);
		$uly = Std::int($ox1 * $s + $oy1 * $c + $cy);
		$blx = Std::int($ox1 * $c - $oy2 * $s + $cx);
		$bly = Std::int($ox1 * $s + $oy2 * $c + $cy);
		$urx = Std::int($ox2 * $c - $oy1 * $s + $cx);
		$ury = Std::int($ox2 * $s + $oy1 * $c + $cy);
		$brx = Std::int($ox2 * $c - $oy2 * $s + $cx);
		$bry = Std::int($ox2 * $s + $oy2 * $c + $cy);
		$points = (new _hx_array(array(_hx_anonymous(array("x" => $ulx, "y" => $uly)), _hx_anonymous(array("x" => $urx, "y" => $ury)), _hx_anonymous(array("x" => $brx, "y" => $bry)), _hx_anonymous(array("x" => $blx, "y" => $bly)))));
		$s1 = "<polygon points=\"";
		{
			$_g1 = 0;
			$_g = $points->length;
			while($_g1 < $_g) {
				$_g1 = $_g1 + 1;
				$i = $_g1 - 1;
				$s1 = _hx_string_or_null($s1) . _hx_string_or_null((_hx_string_rec(_hx_array_get($points, $i)->x, "") . " " . _hx_string_rec(_hx_array_get($points, $i)->y, "")));
				if($i !== $points->length - 1) {
					$s1 = _hx_string_or_null($s1) . " ";
				}
				unset($i);
			}
		}
		$s1 = _hx_string_or_null($s1) . _hx_string_or_null(("\" " . _hx_string_or_null(geometrize_exporter_SvgExporter::$SVG_STYLE_HOOK) . "/>"));
		return $s1;
	}
	public function getCornerPoints() {
		$first = $this->x1;
		$second = $this->x2;
		$xm1 = null;
		if($first < $second) {
			$xm1 = $first;
		} else {
			$xm1 = $second;
		}
		$first1 = $this->x1;
		$second1 = $this->x2;
		$xm2 = null;
		if($first1 > $second1) {
			$xm2 = $first1;
		} else {
			$xm2 = $second1;
		}
		$first2 = $this->y1;
		$second2 = $this->y2;
		$ym1 = null;
		if($first2 < $second2) {
			$ym1 = $first2;
		} else {
			$ym1 = $second2;
		}
		$first3 = $this->y1;
		$second3 = $this->y2;
		$ym2 = null;
		if($first3 > $second3) {
			$ym2 = $first3;
		} else {
			$ym2 = $second3;
		}
		$cx = Std::int(($xm1 + $xm2) / 2);
		$cy = Std::int(($ym1 + $ym2) / 2);
		$ox1 = $xm1 - $cx;
		$ox2 = $xm2 - $cx;
		$oy1 = $ym1 - $cy;
		$oy2 = $ym2 - $cy;
		$rads = $this->angle * Math::$PI / 180.0;
		$c = Math::cos($rads);
		$s = Math::sin($rads);
		$ulx = Std::int($ox1 * $c - $oy1 * $s + $cx);
		$uly = Std::int($ox1 * $s + $oy1 * $c + $cy);
		$blx = Std::int($ox1 * $c - $oy2 * $s + $cx);
		$bly = Std::int($ox1 * $s + $oy2 * $c + $cy);
		$urx = Std::int($ox2 * $c - $oy1 * $s + $cx);
		$ury = Std::int($ox2 * $s + $oy1 * $c + $cy);
		$brx = Std::int($ox2 * $c - $oy2 * $s + $cx);
		$bry = Std::int($ox2 * $s + $oy2 * $c + $cy);
		return (new _hx_array(array(_hx_anonymous(array("x" => $ulx, "y" => $uly)), _hx_anonymous(array("x" => $urx, "y" => $ury)), _hx_anonymous(array("x" => $brx, "y" => $bry)), _hx_anonymous(array("x" => $blx, "y" => $bly)))));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->__dynamics[$m]) && is_callable($this->__dynamics[$m]))
			return call_user_func_array($this->__dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call <'.$m.'>');
	}
	function __toString() { return 'geometrize.shape.RotatedRectangle'; }
}