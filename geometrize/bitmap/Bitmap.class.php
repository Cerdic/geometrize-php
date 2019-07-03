<?php

// Generated by Haxe 3.4.7
class geometrize_bitmap_Bitmap {
	public function __construct() {
		;
	}
	public $width;
	public $height;
	public $data;
	public function getPixel($x, $y) {
		return $this->data[$this->width * $y + $x];
	}
	public function setPixel($x, $y, $color) {
		$this->data[$this->width * $y + $x] = $color;
	}
	public function hclone() {
		$bitmap = new geometrize_bitmap_Bitmap();
		$bitmap->width = $this->width;
		$bitmap->height = $this->height;
		$this1 = (new _hx_array(array()));
		$this1->length = $this->data->length;
		$bitmap->data = $this1;
		{
			$_g1 = 0;
			$_g = $this->data->length;
			while($_g1 < $_g) {
				$_g1 = $_g1 + 1;
				$i = $_g1 - 1;
				$bitmap->data[$i] = $this->data[$i];
				unset($i);
			}
		}
		return $bitmap;
	}
	public function fill($color) {
		$idx = 0;
		while($idx < $this->data->length) {
			$this->data[$idx] = $color >> 24 & 255;
			$this->data[$idx + 1] = $color >> 16 & 255;
			$this->data[$idx + 2] = $color >> 8 & 255;
			$this->data[$idx + 3] = $color & 255;
			$idx = $idx + 4;
		}
	}
	public function getBytes() {
		$bytes = haxe_io_Bytes::alloc($this->data->length * 4);
		$i = 0;
		while($i < $this->data->length) {
			$idx = $i * 4;
			{
				$v = $this->data[$i] >> 24 & 255;
				{
					$this1 = $bytes->b;
					$this1->s[$idx] = chr($v);
					unset($this1);
				}
				unset($v);
			}
			{
				$v1 = $this->data[$i] >> 16 & 255;
				{
					$this2 = $bytes->b;
					$this2->s[$idx + 1] = chr($v1);
					unset($this2);
				}
				unset($v1);
			}
			{
				$v2 = $this->data[$i] >> 8 & 255;
				{
					$this3 = $bytes->b;
					$this3->s[$idx + 2] = chr($v2);
					unset($this3);
				}
				unset($v2);
			}
			{
				$v3 = $this->data[$i] & 255;
				{
					$this4 = $bytes->b;
					$this4->s[$idx + 3] = chr($v3);
					unset($this4);
				}
				unset($v3);
			}
			$i = $i + 1;
			unset($idx);
		}
		return $bytes;
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
	static function create($w, $h, $color) {
		$bitmap = new geometrize_bitmap_Bitmap();
		$bitmap->width = $w;
		$bitmap->height = $h;
		$this1 = (new _hx_array(array()));
		$this1->length = $w * $h;
		$bitmap->data = $this1;
		$i = 0;
		while($i < $bitmap->data->length) {
			$bitmap->data[$i] = $color;
			$i = $i + 1;
		}
		return $bitmap;
	}
	static function createFromBytes($w, $h, $bytes) {
		$bitmap = new geometrize_bitmap_Bitmap();
		if(!($bytes !== null)) {
			throw new HException("FAIL: bytes != null");
		}
		{
			$actual = $bytes->length;
			$expected = $w * $h * 4;
			if($actual !== $expected) {
				throw new HException("FAIL: values are not equal (expected: " . _hx_string_rec($expected, "") . ", actual: " . _hx_string_rec($actual, "") . ")");
			}
		}
		$bitmap->width = $w;
		$bitmap->height = $h;
		$this1 = (new _hx_array(array()));
		$this1->length = Std::int($bytes->length / 4);
		$bitmap->data = $this1;
		$i = 0;
		$x = 0;
		while($i < $bytes->length) {
			{
				$this2 = $bitmap->data;
				$this3 = $bytes->b;
				$red = ord($this3->s[$i]);
				$this4 = $bytes->b;
				$green = ord($this4->s[$i + 1]);
				$this5 = $bytes->b;
				$blue = ord($this5->s[$i + 2]);
				$this6 = $bytes->b;
				$alpha = ord($this6->s[$i + 3]);
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val = null;
				if($red < 0) {
					$val = 0;
				} else {
					if($red > 255) {
						$val = 255;
					} else {
						$val = $red;
					}
				}
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val1 = null;
				if($green < 0) {
					$val1 = 0;
				} else {
					if($green > 255) {
						$val1 = 255;
					} else {
						$val1 = $green;
					}
				}
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val2 = null;
				if($blue < 0) {
					$val2 = 0;
				} else {
					if($blue > 255) {
						$val2 = 255;
					} else {
						$val2 = $blue;
					}
				}
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val3 = null;
				if($alpha < 0) {
					$val3 = 0;
				} else {
					if($alpha > 255) {
						$val3 = 255;
					} else {
						$val3 = $alpha;
					}
				}
				$this2[$x] = ($val << 24) + ($val1 << 16) + ($val2 << 8) + $val3;
				unset($val3,$val2,$val1,$val,$this6,$this5,$this4,$this3,$this2,$red,$green,$blue,$alpha);
			}
			$i = $i + 4;
			$x = $x + 1;
		}
		return $bitmap;
	}
	static function createFromByteArray($w, $h, $bytes) {
		$data = haxe_io_Bytes::alloc($bytes->length);
		$i = 0;
		while($i < $bytes->length) {
			{
				$this1 = $data->b;
				$this1->s[$i] = chr($bytes[$i]);
				unset($this1);
			}
			$i = $i + 1;
		}
		$bitmap = new geometrize_bitmap_Bitmap();
		if(!($data !== null)) {
			throw new HException("FAIL: bytes != null");
		}
		{
			$actual = $data->length;
			$expected = $w * $h * 4;
			if($actual !== $expected) {
				throw new HException("FAIL: values are not equal (expected: " . _hx_string_rec($expected, "") . ", actual: " . _hx_string_rec($actual, "") . ")");
			}
		}
		$bitmap->width = $w;
		$bitmap->height = $h;
		$this2 = (new _hx_array(array()));
		$this2->length = Std::int($data->length / 4);
		$bitmap->data = $this2;
		$i1 = 0;
		$x = 0;
		while($i1 < $data->length) {
			{
				$this3 = $bitmap->data;
				$this4 = $data->b;
				$red = ord($this4->s[$i1]);
				$this5 = $data->b;
				$green = ord($this5->s[$i1 + 1]);
				$this6 = $data->b;
				$blue = ord($this6->s[$i1 + 2]);
				$this7 = $data->b;
				$alpha = ord($this7->s[$i1 + 3]);
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val = null;
				if($red < 0) {
					$val = 0;
				} else {
					if($red > 255) {
						$val = 255;
					} else {
						$val = $red;
					}
				}
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val1 = null;
				if($green < 0) {
					$val1 = 0;
				} else {
					if($green > 255) {
						$val1 = 255;
					} else {
						$val1 = $green;
					}
				}
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val2 = null;
				if($blue < 0) {
					$val2 = 0;
				} else {
					if($blue > 255) {
						$val2 = 255;
					} else {
						$val2 = $blue;
					}
				}
				if(!true) {
					throw new HException("FAIL: min <= max");
				}
				$val3 = null;
				if($alpha < 0) {
					$val3 = 0;
				} else {
					if($alpha > 255) {
						$val3 = 255;
					} else {
						$val3 = $alpha;
					}
				}
				$this3[$x] = ($val << 24) + ($val1 << 16) + ($val2 << 8) + $val3;
				unset($val3,$val2,$val1,$val,$this7,$this6,$this5,$this4,$this3,$red,$green,$blue,$alpha);
			}
			$i1 = $i1 + 4;
			$x = $x + 1;
		}
		return $bitmap;
	}
	function __toString() { return 'geometrize.bitmap.Bitmap'; }
}