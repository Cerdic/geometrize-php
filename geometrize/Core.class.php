<?php

// Generated by Haxe 3.4.7
class geometrize_Core {
	public function __construct(){}
	static function computeColor($target, $current, $lines, $alpha) {
		if(!($target !== null)) {
			throw new HException("FAIL: target != null");
		}
		if(!($current !== null)) {
			throw new HException("FAIL: current != null");
		}
		if(!($lines !== null)) {
			throw new HException("FAIL: lines != null");
		}
		if(!($alpha >= 0)) {
			throw new HException("FAIL: alpha >= 0");
		}
		$totalRed = 0;
		$totalGreen = 0;
		$totalBlue = 0;
		$count = 0;
		$f = 65535 / $alpha;
		$a = Std::int($f);
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
						$t = $target->data[$target->width * $y + $x];
						$c = $current->data[$current->width * $y + $x];
						$totalRed = $totalRed + ((($t >> 24 & 255) - ($c >> 24 & 255)) * $a + ($c >> 24 & 255) * 257);
						$totalGreen = $totalGreen + ((($t >> 16 & 255) - ($c >> 16 & 255)) * $a + ($c >> 16 & 255) * 257);
						$totalBlue = $totalBlue + ((($t >> 8 & 255) - ($c >> 8 & 255)) * $a + ($c >> 8 & 255) * 257);
						$count = $count + 1;
						unset($x,$t,$c);
					}
					unset($_g2,$_g1);
				}
				unset($y,$line);
			}
		}
		if($count === 0) {
			return 0;
		}
		$value = Std::int($totalRed / $count) >> 8;
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$r = null;
		if($value < 0) {
			$r = 0;
		} else {
			if($value > 255) {
				$r = 255;
			} else {
				$r = $value;
			}
		}
		$value1 = Std::int($totalGreen / $count) >> 8;
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$g = null;
		if($value1 < 0) {
			$g = 0;
		} else {
			if($value1 > 255) {
				$g = 255;
			} else {
				$g = $value1;
			}
		}
		$value2 = Std::int($totalBlue / $count) >> 8;
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$b = null;
		if($value2 < 0) {
			$b = 0;
		} else {
			if($value2 > 255) {
				$b = 255;
			} else {
				$b = $value2;
			}
		}
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$tmp = null;
		if($r < 0) {
			$tmp = 0;
		} else {
			if($r > 255) {
				$tmp = 255;
			} else {
				$tmp = $r;
			}
		}
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$tmp1 = null;
		if($g < 0) {
			$tmp1 = 0;
		} else {
			if($g > 255) {
				$tmp1 = 255;
			} else {
				$tmp1 = $g;
			}
		}
		if(!true) {
			throw new HException("FAIL: min <= max");
		}
		$tmp2 = null;
		if($b < 0) {
			$tmp2 = 0;
		} else {
			if($b > 255) {
				$tmp2 = 255;
			} else {
				$tmp2 = $b;
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
	static function differenceFull($first, $second) {
		if(!($first !== null)) {
			throw new HException("FAIL: first != null");
		}
		if(!($second !== null)) {
			throw new HException("FAIL: second != null");
		}
		{
			$actual = $first->width;
			$expected = $second->width;
			if($actual !== $expected) {
				throw new HException("FAIL: values are not equal (expected: " . _hx_string_rec($expected, "") . ", actual: " . _hx_string_rec($actual, "") . ")");
			}
		}
		{
			$actual1 = $first->height;
			$expected1 = $second->height;
			if($actual1 !== $expected1) {
				throw new HException("FAIL: values are not equal (expected: " . _hx_string_rec($expected1, "") . ", actual: " . _hx_string_rec($actual1, "") . ")");
			}
		}
		$total = 0;
		$width = $first->width;
		$height = $first->height;
		{
			$_g1 = 0;
			$_g = $height;
			while($_g1 < $_g) {
				$_g1 = $_g1 + 1;
				$y = $_g1 - 1;
				{
					$_g3 = 0;
					$_g2 = $width;
					while($_g3 < $_g2) {
						$_g3 = $_g3 + 1;
						$x = $_g3 - 1;
						$f = $first->data[$first->width * $y + $x];
						$s = $second->data[$second->width * $y + $x];
						$dr = ($f >> 24 & 255) - ($s >> 24 & 255);
						$dg = ($f >> 16 & 255) - ($s >> 16 & 255);
						$db = ($f >> 8 & 255) - ($s >> 8 & 255);
						$da = ($f & 255) - ($s & 255);
						$total = $total + ($dr * $dr + $dg * $dg + $db * $db + $da * $da);
						unset($x,$s,$f,$dr,$dg,$db,$da);
					}
					unset($_g3,$_g2);
				}
				unset($y);
			}
		}
		return Math::sqrt($total / ($width * $height * 4.0)) / 255;
	}
	static function differencePartial($target, $before, $after, $score, $lines) {
		if(!($target !== null)) {
			throw new HException("FAIL: target != null");
		}
		if(!($before !== null)) {
			throw new HException("FAIL: before != null");
		}
		if(!($after !== null)) {
			throw new HException("FAIL: after != null");
		}
		if(!($lines !== null)) {
			throw new HException("FAIL: lines != null");
		}
		$width = $target->width;
		$height = $target->height;
		$rgbaCount = $width * $height * 4;
		$total = Math::pow($score * 255, 2) * $rgbaCount;
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
						$t = $target->data[$target->width * $y + $x];
						$b = $before->data[$before->width * $y + $x];
						$a = $after->data[$after->width * $y + $x];
						$dtbr = ($t >> 24 & 255) - ($b >> 24 & 255);
						$dtbg = ($t >> 16 & 255) - ($b >> 16 & 255);
						$dtbb = ($t >> 8 & 255) - ($b >> 8 & 255);
						$dtba = ($t & 255) - ($b & 255);
						$dtar = ($t >> 24 & 255) - ($a >> 24 & 255);
						$dtag = ($t >> 16 & 255) - ($a >> 16 & 255);
						$dtab = ($t >> 8 & 255) - ($a >> 8 & 255);
						$dtaa = ($t & 255) - ($a & 255);
						$total = $total - ($dtbr * $dtbr + $dtbg * $dtbg + $dtbb * $dtbb + $dtba * $dtba);
						$total = $total + ($dtar * $dtar + $dtag * $dtag + $dtab * $dtab + $dtaa * $dtaa);
						unset($x,$t,$dtbr,$dtbg,$dtbb,$dtba,$dtar,$dtag,$dtab,$dtaa,$b,$a);
					}
					unset($_g2,$_g1);
				}
				unset($y,$line);
			}
		}
		return Math::sqrt($total / $rgbaCount) / 255;
	}
	static function bestRandomState($shapes, $alpha, $n, $target, $current, $buffer, $lastScore) {
		$bestEnergy = 0;
		$bestState = null;
		{
			$_g1 = 0;
			$_g = $n;
			while($_g1 < $_g) {
				$_g1 = $_g1 + 1;
				$i = $_g1 - 1;
				$state = new geometrize_State(geometrize_shape_ShapeFactory::randomShapeOf($shapes, $current->width, $current->height), $alpha, $target, $current, $buffer);
				$energy = $state->energy($lastScore);
				$tmp = null;
				if($i !== 0) {
					$tmp = $energy < $bestEnergy;
				} else {
					$tmp = true;
				}
				if($tmp) {
					$bestEnergy = $energy;
					$bestState = $state;
				}
				unset($tmp,$state,$i,$energy);
			}
		}
		return $bestState;
	}
	static function bestHillClimbState($shapes, $alpha, $n, $age, $target, $current, $buffer, $lastScore) {
		$state = geometrize_Core::bestRandomState($shapes, $alpha, $n, $target, $current, $buffer, $lastScore);
		$state = geometrize_Core::hillClimb($state, $age, $lastScore);
		return $state;
	}
	static function hillClimb($state, $maxAge, $lastScore) {
		if(!($state !== null)) {
			throw new HException("FAIL: state != null");
		}
		if(!($maxAge >= 0)) {
			throw new HException("FAIL: maxAge >= 0");
		}
		$state1 = $state->hclone();
		$bestState = $state1->hclone();
		$bestEnergy = $state1->energy($lastScore);
		$age = 0;
		while($age < $maxAge) {
			$undo = $state1->mutate();
			$energy = $state1->energy($lastScore);
			if($energy >= $bestEnergy) {
				$state1 = $undo;
			} else {
				$bestEnergy = $energy;
				$bestState = $state1->hclone();
				$age = -1;
			}
			$age = $age + 1;
			unset($undo,$energy);
		}
		return $bestState;
	}
	static function energy($shape, $alpha, $target, $current, $buffer, $score) {
		if(!($shape !== null)) {
			throw new HException("FAIL: shape != null");
		}
		if(!($target !== null)) {
			throw new HException("FAIL: target != null");
		}
		if(!($current !== null)) {
			throw new HException("FAIL: current != null");
		}
		if(!($buffer !== null)) {
			throw new HException("FAIL: buffer != null");
		}
		$lines = $shape->rasterize();
		if (!isset($shape->color)) {
			$shape->color = geometrize_Core::computeColor($target, $current, $lines, $alpha);
		}
		geometrize_rasterizer_Rasterizer::copyLines($buffer, $current, $lines);
		geometrize_rasterizer_Rasterizer::drawLines($buffer, $shape->color, $lines);
		return geometrize_Core::differencePartial($target, $current, $buffer, $score, $lines);
	}
	function __toString() { return 'geometrize.Core'; }
}
