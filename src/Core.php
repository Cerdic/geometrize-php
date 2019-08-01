<?php

namespace Cerdic\Geometrize;

use \Cerdic\Geometrize\Bitmap;
use \Cerdic\Geometrize\State;
use \Cerdic\Geometrize\Rasterizer\Rasterizer;
use \Cerdic\Geometrize\Shape\ShapeFactory;

class Core {

	/**
	 * @param \Cerdic\Geometrize\Bitmap $target
	 * @param \Cerdic\Geometrize\Bitmap $current
	 * @param array $lines
	 * @param int $alpha
	 * @return int
	 * @throws \Exception
	 */
	static function computeColor($target, $current, $lines, $alpha){
		if (!($target!==null)){
			throw new \Exception("FAIL: target != null");
		}
		if (!($current!==null)){
			throw new \Exception("FAIL: current != null");
		}
		if (!($lines!==null)){
			throw new \Exception("FAIL: lines != null");
		}
		if (!($alpha>=0)){
			throw new \Exception("FAIL: alpha >= 0");
		}
		$totalRed = 0;
		$totalGreen = 0;
		$totalBlue = 0;
		$count = 0;

		if ($alpha < 255) {
			$f = 256 * 255 /$alpha;
			$a = intval($f);

			foreach($lines as $line){
				$y = $line['y'];
				for ($x=$line['x1']; $x<=$line['x2']; $x++) {
					$t = $target->data[$y][$x];
					$c = $current->data[$y][$x];
					$totalRed += (($t >> 24 & 255)-($c >> 24 & 255))*$a+($c >> 24 & 255)*256;
					$totalGreen += (($t >> 16 & 255)-($c >> 16 & 255))*$a+($c >> 16 & 255)*256;
					$totalBlue += (($t >> 8 & 255)-($c >> 8 & 255))*$a+($c >> 8 & 255)*256;
					$count++;
				}
			}
			$totalRed = $totalRed >> 8;
			$totalGreen = $totalGreen >> 8;
			$totalBlue = $totalBlue >> 8;
		}
		else {
			foreach($lines as $line){
				$y = $line['y'];
				for ($x=$line['x1']; $x<=$line['x2']; $x++) {
					$t = $target->data[$y][$x];
					$totalRed += ($t >> 24 & 255);
					$totalGreen += ($t >> 16 & 255);
					$totalBlue += ($t >> 8 & 255);
					$count++;
				}
			}
		}

		if ($count===0){
			return 0;
		}

		$r = intval(round($totalRed/$count));
		$r = min(255, $r);

		$g = intval(round($totalGreen/$count));
		$g = min(255, $g);

		$b = intval(round($totalBlue/$count));
		$b = min(255, $b);

		return ($r << 24) + ($g << 16) + ($b << 8) + $alpha;
	}

	/**
	 * @param \Cerdic\Geometrize\Bitmap $target
	 * @param \Cerdic\Geometrize\Bitmap $current
	 * @return int
	 * @throws \Exception
	 */
	static function differenceFull($target, $current){

		$current->errorCache = [];
		$total = 0;
		$width = $target->width;
		$height = $target->height;
		for ($y = 0; $y<$height; $y++){
			for ($x = 0; $x<$width; $x++){
				$t = &$target->data[$y][$x];
				$c = &$current->data[$y][$x];
				$e = 0;
				foreach ([24,16,8,0] as $k){
					$dk = ($t>>$k & 255)-($c>>$k & 255);
					if ($dk<0){
						$dk *= -1;
					}
					$e += $dk;
				}
				$total += ($current->errorCache[$y][$x] = $e);
			}
		}
		return $total;
	}

	/**
	 * @param \Cerdic\Geometrize\Bitmap $target
	 * @param \Cerdic\Geometrize\Bitmap $before
	 * @param \Cerdic\Geometrize\Bitmap $after
	 * @param int $score
	 * @param array $lines
	 * @param null|int $bestScore
	 * @return int
	 */
	static function differencePartial($target, $before, $after, $score, $lines, $bestScore = null){

		$total = $score;
		foreach ($lines as &$line) {
			$y = $line['y'];
			$_xe = $line['x2']+1;
			for ($x = $line['x1']; $x<$_xe; $x++){
				if (!isset($before->errorCache[$y][$x])){
					$e = 0;
					$t = &$target->data[$y][$x];
					$b = &$before->data[$y][$x];
					foreach ([24,16,8,0] as $k){
						$dk = ($t>>$k & 255)-($b>>$k & 255);
						if ($dk<0){
							$dk *= -1;
						}
						$e += $dk;
					}
					$before->errorCache[$y][$x] = $e;
				}
				$total -= $before->errorCache[$y][$x];
			}
		}
		if (!is_null($bestScore) && $total>$bestScore){
			return $total;
		}

		foreach ($lines as &$line) {
			$y = $line['y'];
			$_xe = $line['x2']+1;
			for ($x = $line['x1']; $x<$_xe; $x++){
				$t = &$target->data[$y][$x];
				$a = &$after->data[$y][$x];
				foreach ([24,16,8,0] as $k){
					$dk = ($t>>$k & 255)-($a>>$k & 255);
					if ($dk<0){
						$dk *= -1;
					}
					$total += $dk;
				}
			}
			if (!is_null($bestScore) && $total>$bestScore){
				return $total;
			}
		}

		return $total;
	}

	/**
	 * @param array $shapes
	 * @param int $alpha
	 * @param int $nRandom
	 * @param \Cerdic\Geometrize\Bitmap $target
	 * @param \Cerdic\Geometrize\Bitmap $current
	 * @param \Cerdic\Geometrize\Bitmap $buffer
	 * @param int $lastScore
	 * @return \Cerdic\Geometrize\State
	 * @throws \Exception
	 */
	static function bestRandomState($shapes, $shapeSizeFactor, $alpha, $nRandom, $target, $current, $buffer, $lastScore){
		$bestEnergy = null;
		$bestState = null;

		$nRandom = max($nRandom, 1);

		for ($i = 0; $i<$nRandom; $i++){
			$state = new State(ShapeFactory::randomShapeOf($shapes, $current->width, $current->height, $shapeSizeFactor), $alpha, $target, $current, $buffer);
			$energy = $state->energy($lastScore, $bestEnergy);
			if (is_null($bestEnergy) || $energy<$bestEnergy){
				$bestEnergy = $energy;
				$bestState = $state;
			}
		}

		return $bestState;
	}

	/**
	 * @param array $shapes
	 * @param float $shapeSizeFactor
	 * @param int $alpha
	 * @param int $nRandom
	 * @param int $maxMutationAge
	 * @param \Cerdic\Geometrize\Bitmap $target
	 * @param \Cerdic\Geometrize\Bitmap $current
	 * @param \Cerdic\Geometrize\Bitmap $buffer
	 * @param int $lastScore
	 * @return \Cerdic\Geometrize\State
	 * @throws \Exception
	 */
	static function bestHillClimbState($shapes, $shapeSizeFactor, $alpha, $nRandom, $maxMutationAge, $target, $current, $buffer, $lastScore){
		$state = Core::bestRandomState($shapes, $shapeSizeFactor, $alpha, $nRandom, $target, $current, $buffer, $lastScore);
		$state = Core::hillClimb($state, $maxMutationAge, $lastScore);
		return $state;
	}

	/**
	 * @param \Cerdic\Geometrize\State $state
	 * @param int $maxAge
	 * @param int $lastScore
	 * @return \Cerdic\Geometrize\State
	 * @throws \Exception
	 */
	static function hillClimb($state, $maxAge, $lastScore){
		if (!($state!==null)){
			throw new \Exception("FAIL: state != null");
		}
		if (!($maxAge>=0)){
			throw new \Exception("FAIL: maxAge >= 0");
		}

		$bestEnergy = $state->energy($lastScore);
		$bestState = clone $state;

		$age = 0;
		while ($age++<$maxAge){
			$state1 = clone $bestState;
			$state1->mutate();
			$energy = $state1->energy($lastScore, $bestEnergy);

			if ($energy<$bestEnergy){
				$bestEnergy = $energy;
				$bestState = $state1;
				$age = 0;
			}
		}

		return $bestState;
	}

	/**
	 * @param \Cerdic\Geometrize\Shape\Shape $shape
	 * @param int $alpha
	 * @param \Cerdic\Geometrize\Bitmap $target
	 * @param \Cerdic\Geometrize\Bitmap $current
	 * @param \Cerdic\Geometrize\Bitmap $buffer
	 * @param int $score
	 * @param null|int $bestScore
	 * @return int
	 * @throws \Exception
	 */
	static function energy(&$shape, $alpha, $target, $current, $buffer, $score, $bestScore = null){
		if (!($shape!==null)){
			throw new \Exception("FAIL: shape != null");
		}
		if (!($target!==null)){
			throw new \Exception("FAIL: target != null");
		}
		if (!($current!==null)){
			throw new \Exception("FAIL: current != null");
		}
		if (!($buffer!==null)){
			throw new \Exception("FAIL: buffer != null");
		}
		$lines = $shape->rasterize();
		if (!isset($shape->color)){
			$shape->color = Core::computeColor($target, $current, $lines, $alpha);
		}
		// copyLines only if opacity!=1 (speed issue with no transparency in shapes)
		if ($shape->color & 255!==255){
			Rasterizer::copyLines($buffer, $current, $lines);
		}
		Rasterizer::drawLines($buffer, $shape->color, $lines);
		return Core::differencePartial($target, $current, $buffer, $score, $lines, $bestScore);
	}

}
