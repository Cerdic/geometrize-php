<?php

namespace Cerdic\Geometrize;

use \Cerdic\Geometrize\Bitmap;
use \Cerdic\Geometrize\Bitmap\DominantColours;
use \Cerdic\Geometrize\Core;
use \Cerdic\Geometrize\Rasterizer\Rasterizer;
use \Cerdic\Geometrize\Shape\Rectangle;

class Model {
	/**
	 * @var int
	 */
	protected $width;

	/**
	 * @var int
	 */
	protected $height;

	/**
	 * @var int|bool
	 */
	protected $backgroundColor;

	/**
	 * @var \Cerdic\Geometrize\Bitmap
	 */
	protected $target;

	/**
	 * @var \Cerdic\Geometrize\Bitmap
	 */
	protected $current;

	/**
	 * @var \Cerdic\Geometrize\Bitmap
	 */
	protected $buffer;

	/**
	 * @var float
	 */
	protected $shapeSizeFactor;

	/**
	 * @var float
	 */
	protected $score;

	/**
	 * Model constructor.
	 * @param Bitmap $target
	 * @param int|bool $backgroundColor
	 * @throws \Exception
	 */
	public function __construct($target, $backgroundColor){
		if (!($target!==null)){
			throw new \Exception("FAIL: target != null");
		}
		$this->width = $target->width;
		$this->height = $target->height;
		$this->target = $target;

		$this->backgroundColor = $backgroundColor;

		// automatic best backgroundColor (lowering the error) ?
		if ($this->backgroundColor === true) {
			$bg = new Rectangle($this->width, $this->height, 1, true);
			$dominantColours = DominantColours::dominantColours(3, $this->target, $bg->rasterize());
			$this->backgroundColor = reset($dominantColours);
		}

		$this->current = Bitmap::create($this->width,$this->height,$this->backgroundColor);
		$this->score = Core::differenceFull($target, $this->current);

		$this->buffer = clone $this->current;

		$this->shapeSizeFactor = 1.0;
	}

	/**
	 * @param array $shapeTypes
	 * @param int $alpha
	 * @param int $nRandom
	 * @param int $maxMutationAge
	 * @return array
	 * @throws \Exception
	 */
	public function step($shapeTypes, $alpha, $nRandom, $maxMutationAge){
		$state = Core::bestHillClimbState($shapeTypes, $this->shapeSizeFactor, $alpha, $nRandom, $maxMutationAge, $this->target, $this->current, $this->buffer, $this->score);
		return $this->addShape($state->getShape(), $state->getAlpha());
	}

	/**
	 * @param \Cerdic\Geometrize\Shape\Shape $shape
	 * @param int $alpha
	 * @return array
	 * @throws \Exception
	 */
	public function addShape($shape, $alpha){
		if (!($shape!==null)){
			throw new \Exception("FAIL: shape != null");
		}
		$before = clone $this->current;

		$lines = $shape->rasterize();
		if (!isset($shape->color)){
			$shape->color = Core::computeColor($this->target, $this->current, $lines, $alpha);
		}

		Rasterizer::drawLines($this->current, $shape->color, $lines);
		$this->score = Core::differencePartial($this->target, $before, $this->current, $this->score, $lines);
		$score_normalization = $before->width * $before->height * 4 * 255;
		$result = ["score" => $this->score/$score_normalization, "color" => $shape->color, "shape" => $shape];

		$this->shapeSizeFactor = 0.75 * $this->shapeSizeFactor + 0.25 * $shape->getSizeFactor();
		$this->shapeSizeFactor = max(min($this->shapeSizeFactor, 1.0),0.1);
		return $result;
	}

	/**
	 * @return \Cerdic\Geometrize\Bitmap
	 */
	public function getCurrent() {
		return $this->current;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return bool|int
	 */
	public function getBackgroundColor() {
		return $this->backgroundColor;
	}

}
