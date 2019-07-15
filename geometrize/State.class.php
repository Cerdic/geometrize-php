<?php

// Generated by Haxe 3.4.7
class geometrize_State {
	/**
	 * @var geometrize_shape_Shape
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
	 * @var geometrize_bitmap_Bitmap
	 */
	protected $target;

	/**
	 * @var geometrize_bitmap_Bitmap
	 */
	protected $current;

	/**
	 * @var geometrize_bitmap_Bitmap
	 */
	protected $buffer;

	public function __construct($shape, $alpha, $target, $current, $buffer){
		if (!($shape!==null)){
			throw new HException("FAIL: shape != null");
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
	 * @throws HException
	 */
	public function energy($lastScore, $bestScore = null){
		if ($this->score<0){
			$this->score = geometrize_Core::energy($this->shape, $this->alpha, $this->target, $this->current, $this->buffer, $lastScore, $bestScore);
		}
		return $this->score;
	}

	/**
	 * @return geometrize_State
	 */
	public function mutate(){
		$oldState = clone $this;
		$this->shape->mutate();
		// force score recomputing as we mutated
		$this->score = -1;
		return $oldState;
	}

	/**
	 * @return geometrize_shape_Shape
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

	public function hclone(){
		return clone $this;
	}

	public function __clone() {
		$this->shape = clone $this->shape;
		/*
		$this->target = clone $this->target;
		$this->current = clone $this->current;
		$this->buffer = clone $this->buffer;
		*/
  }


	function __toString(){
		return 'geometrize.State';
	}
}
