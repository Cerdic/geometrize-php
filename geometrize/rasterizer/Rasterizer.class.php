<?php

// Generated by Haxe 3.4.7
class geometrize_rasterizer_Rasterizer {
	public function __construct(){}
	static function drawLines($image, $c, $lines) {
		if(!($image !== null)) {
			throw new HException("FAIL: image != null");
		}
		if(!($lines !== null)) {
			throw new HException("FAIL: lines != null");
		}
		$sr = $c >> 24 & 255;
		$sr = $sr | $sr << 8;
		$sr = $sr * ($c & 255);
		$sr = Std::int($sr / 255);
		$sg = $c >> 16 & 255;
		$sg = $sg | $sg << 8;
		$sg = $sg * ($c & 255);
		$sg = Std::int($sg / 255);
		$sb = $c >> 8 & 255;
		$sb = $sb | $sb << 8;
		$sb = $sb * ($c & 255);
		$sb = Std::int($sb / 255);
		$sa = $c & 255;
		$sa = $sa | $sa << 8;
		{
			$_g = 0;
			while($_g < $lines->length) {
				$line = $lines[$_g];
				$_g = $_g + 1;
				$y = $line->y;
				$ma = 65535;
				$m = 65535;
				$as = ($m - $sa * ($ma / $m)) * 257;
				$a = Std::int($as);
				{
					$_g2 = $line->x1;
					$_g1 = $line->x2 + 1;
					while($_g2 < $_g1) {
						$_g2 = $_g2 + 1;
						$x = $_g2 - 1;
						$d = $image->data[$image->width * $y + $x];
						$dr = $d >> 24 & 255;
						$dg = $d >> 16 & 255;
						$db = $d >> 8 & 255;
						$da = $d & 255;
						$int = $dr * $a + $sr * $ma;
						$r = null;
						if($int < 0) {
							$r = 4294967296.0 + $int;
						} else {
							$r = $int + 0.0;
						}
						$int1 = $m;
						$r1 = null;
						if($int1 < 0) {
							$r1 = 4294967296.0 + $int1;
						} else {
							$r1 = $int1 + 0.0;
						}
						$r2 = Std::int($r / $r1) >> 8;
						$int2 = $dg * $a + $sg * $ma;
						$g = null;
						if($int2 < 0) {
							$g = 4294967296.0 + $int2;
						} else {
							$g = $int2 + 0.0;
						}
						$int3 = $m;
						$g1 = null;
						if($int3 < 0) {
							$g1 = 4294967296.0 + $int3;
						} else {
							$g1 = $int3 + 0.0;
						}
						$g2 = Std::int($g / $g1) >> 8;
						$int4 = $db * $a + $sb * $ma;
						$b = null;
						if($int4 < 0) {
							$b = 4294967296.0 + $int4;
						} else {
							$b = $int4 + 0.0;
						}
						$int5 = $m;
						$b1 = null;
						if($int5 < 0) {
							$b1 = 4294967296.0 + $int5;
						} else {
							$b1 = $int5 + 0.0;
						}
						$b2 = Std::int($b / $b1) >> 8;
						$int6 = $da * $a + $sa * $ma;
						$a1 = null;
						if($int6 < 0) {
							$a1 = 4294967296.0 + $int6;
						} else {
							$a1 = $int6 + 0.0;
						}
						$int7 = $m;
						$a2 = null;
						if($int7 < 0) {
							$a2 = 4294967296.0 + $int7;
						} else {
							$a2 = $int7 + 0.0;
						}
						$a3 = Std::int($a1 / $a2) >> 8;
						{
							if(!true) {
								throw new HException("FAIL: min <= max");
							}
							$color = null;
							if($r2 < 0) {
								$color = 0;
							} else {
								if($r2 > 255) {
									$color = 255;
								} else {
									$color = $r2;
								}
							}
							if(!true) {
								throw new HException("FAIL: min <= max");
							}
							$color1 = null;
							if($g2 < 0) {
								$color1 = 0;
							} else {
								if($g2 > 255) {
									$color1 = 255;
								} else {
									$color1 = $g2;
								}
							}
							if(!true) {
								throw new HException("FAIL: min <= max");
							}
							$color2 = null;
							if($b2 < 0) {
								$color2 = 0;
							} else {
								if($b2 > 255) {
									$color2 = 255;
								} else {
									$color2 = $b2;
								}
							}
							if(!true) {
								throw new HException("FAIL: min <= max");
							}
							$color3 = null;
							if($a3 < 0) {
								$color3 = 0;
							} else {
								if($a3 > 255) {
									$color3 = 255;
								} else {
									$color3 = $a3;
								}
							}
							$image->data[$image->width * $y + $x] = ($color << 24) + ($color1 << 16) + ($color2 << 8) + $color3;
							unset($color3,$color2,$color1,$color);
						}
						unset($x,$r2,$r1,$r,$int7,$int6,$int5,$int4,$int3,$int2,$int1,$int,$g2,$g1,$g,$dr,$dg,$db,$da,$d,$b2,$b1,$b,$a3,$a2,$a1);
					}
					unset($_g2,$_g1);
				}
				unset($y,$ma,$m,$line,$as,$a);
			}
		}
	}
	static function copyLines($destination, $source, $lines) {
		if(!($destination !== null)) {
			throw new HException("FAIL: destination != null");
		}
		if(!($source !== null)) {
			throw new HException("FAIL: source != null");
		}
		if(!($lines !== null)) {
			throw new HException("FAIL: lines != null");
		}
		{
			$_g = 0;
			while($_g < $lines->length) {
				$line = $lines[$_g];
				$_g = $_g + 1;
				$y = $line->y;
				{
					$_g2 = $line->x1;
					$_g1 = $line->x2 + 1;
					while($_g2 < $_g1) {
						$_g2 = $_g2 + 1;
						$x = $_g2 - 1;
						$destination->data[$destination->width * $y + $x] = $source->data[$source->width * $y + $x];
						unset($x);
					}
					unset($_g2,$_g1);
				}
				unset($y,$line);
			}
		}
	}
	static function bresenham($x1, $y1, $x2, $y2) {
		$dx = $x2 - $x1;
		$ix = null;
		if($dx > 0) {
			$ix = 1;
		} else {
			$ix = 0;
		}
		$ix1 = null;
		if($dx < 0) {
			$ix1 = 1;
		} else {
			$ix1 = 0;
		}
		$ix2 = $ix - $ix1;
		$dx1 = null;
		if($dx < 0) {
			$dx1 = -$dx;
		} else {
			$dx1 = $dx;
		}
		$dx = $dx1 << 1;
		$dy = $y2 - $y1;
		$iy = null;
		if($dy > 0) {
			$iy = 1;
		} else {
			$iy = 0;
		}
		$iy1 = null;
		if($dy < 0) {
			$iy1 = 1;
		} else {
			$iy1 = 0;
		}
		$iy2 = $iy - $iy1;
		$dy1 = null;
		if($dy < 0) {
			$dy1 = -$dy;
		} else {
			$dy1 = $dy;
		}
		$dy = $dy1 << 1;
		$points = (new _hx_array(array()));
		$points->push(_hx_anonymous(array("x" => $x1, "y" => $y1)));
		if($dx >= $dy) {
			$error = $dy - ($dx >> 1);
			while($x1 !== $x2) {
				$tmp = null;
				if($error >= 0) {
					if($error === 0) {
						$tmp = $ix2 > 0;
					} else {
						$tmp = true;
					}
				} else {
					$tmp = false;
				}
				if($tmp) {
					$error = $error - $dx;
					$y1 = $y1 + $iy2;
				}
				$error = $error + $dy;
				$x1 = $x1 + $ix2;
				$points->push(_hx_anonymous(array("x" => $x1, "y" => $y1)));
				unset($tmp);
			}
		} else {
			$error1 = $dx - ($dy >> 1);
			while($y1 !== $y2) {
				$tmp1 = null;
				if($error1 >= 0) {
					if($error1 === 0) {
						$tmp1 = $iy2 > 0;
					} else {
						$tmp1 = true;
					}
				} else {
					$tmp1 = false;
				}
				if($tmp1) {
					$error1 = $error1 - $dy;
					$x1 = $x1 + $ix2;
				}
				$error1 = $error1 + $dx;
				$y1 = $y1 + $iy2;
				$points->push(_hx_anonymous(array("x" => $x1, "y" => $y1)));
				unset($tmp1);
			}
		}
		return $points;
	}
	static function scanlinesForPolygon($points) {
		$lines = (new _hx_array(array()));
		$edges = (new _hx_array(array()));
		{
			$_g1 = 0;
			$_g = $points->length;
			while($_g1 < $_g) {
				$_g1 = $_g1 + 1;
				$i = $_g1 - 1;
				$p1 = $points[$i];
				$p2 = null;
				if($i === $points->length - 1) {
					$p2 = $points[0];
				} else {
					$p2 = $points[$i + 1];
				}
				$p1p2 = geometrize_rasterizer_Rasterizer::bresenham($p1->x, $p1->y, $p2->x, $p2->y);
				$edges = $edges->concat($p1p2);
				unset($p2,$p1p2,$p1,$i);
			}
		}
		$yToXs = new haxe_ds_IntMap();
		{
			$_g2 = 0;
			while($_g2 < $edges->length) {
				$point = $edges[$_g2];
				$_g2 = $_g2 + 1;
				$s = $yToXs->get($point->y);
				if($s !== null) {
					geometrize__ArraySet_ArraySet_Impl_::add($s, $point->x);
				} else {
					$s = geometrize__ArraySet_ArraySet_Impl_::create(null);
					geometrize__ArraySet_ArraySet_Impl_::add($s, $point->x);
					$yToXs->set($point->y, $s);
				}
				unset($s,$point);
			}
		}
		{
			$key = $yToXs->keys();
			while($key->hasNext()) {
				$key1 = $key->next();
				$a = geometrize__ArraySet_ArraySet_Impl_::toArray($yToXs->get($key1));
				$minMaxElements = null;
				$minMaxElements1 = null;
				if($a !== null) {
					$minMaxElements1 = $a->length === 0;
				} else {
					$minMaxElements1 = true;
				}
				if($minMaxElements1) {
					$minMaxElements = _hx_anonymous(array("x" => 0, "y" => 0));
				} else {
					$min = $a[0];
					$max = $a[0];
					{
						$_g3 = 0;
						while($_g3 < $a->length) {
							$value = $a[$_g3];
							$_g3 = $_g3 + 1;
							if($min > $value) {
								$min = $value;
							}
							if($max < $value) {
								$max = $value;
							}
							unset($value);
						}
						unset($_g3);
					}
					$minMaxElements = _hx_anonymous(array("x" => $min, "y" => $max));
					unset($min,$max);
				}
				$lines->push(new geometrize_rasterizer_Scanline($key1, $minMaxElements->x, $minMaxElements->y));
				unset($minMaxElements1,$minMaxElements,$key1,$a);
			}
		}
		return $lines;
	}
	function __toString() { return 'geometrize.rasterizer.Rasterizer'; }
}