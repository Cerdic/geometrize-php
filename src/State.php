<?php

namespace Cerdic\Geometrize;

use \Cerdic\Geometrize\Core;

class State {
	/**
	 * @var \geometrize_shape_Shape
	 */
	protected $shape;

	/**
	 * @var int
	 */
	protected $alpha;

	/**
	 * @var int
	 */
	protected $score;

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

	public function __construct($shape, $alpha, $target, $current, $buffer){
		if (!($shape!==null)){
			throw new \Exception("FAIL: shape != null");
		}
		$this->shape = $shape;
		$this->alpha = $alpha;
		$this->score = -1;
		$this->target = $target;
		$this->current = $current;
		$this->buffer = $buffer;
	}

	/**
	 * @param int $lastScore
	 * @param null|int $bestScore
	 * @return int
	 * @throws \Exception
	 */
	public function energy($lastScore, $bestScore = null){
		if ($this->score<0){
			$this->score = Core::energy($this->shape, $this->alpha, $this->target, $this->current, $this->buffer, $lastScore, $bestScore);
		}
		return $this->score;
	}

	/**
	 * @return \Cerdic\Geometrize\State
	 */
	public function mutate(){
		$this->shape->mutate();
		// force score recomputing as we mutated
		$this->score = -1;
	}

	/**
	 * @return \geometrize_shape_Shape
	 */
	public function getShape() {
		return $this->shape;
	}

	/**
	 * @return int
	 */
	public function getAlpha() {
		return $this->alpha;
	}

	public function __clone() {
		$this->shape = clone $this->shape;
		/*
		$this->target = clone $this->target;
		$this->current = clone $this->current;
		$this->buffer = clone $this->buffer;
		*/
  }

}
