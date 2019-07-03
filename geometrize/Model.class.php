<?php

// Generated by Haxe 3.4.7
class geometrize_Model {
	public function __construct($target, $backgroundColor) {
		if(!php_Boot::$skip_constructor) {
		if(!($target !== null)) {
			throw new HException("FAIL: target != null");
		}
		$this->width = $target->width;
		$this->height = $target->height;
		$this->target = $target;
		$w = $target->width;
		$h = $target->height;
		$bitmap = new geometrize_bitmap_Bitmap();
		$bitmap->width = $w;
		$bitmap->height = $h;
		$this1 = (new _hx_array(array()));
		$this1->length = $w * $h;
		$bitmap->data = $this1;
		$i = 0;
		while($i < $bitmap->data->length) {
			$bitmap->data[$i] = $backgroundColor;
			$i = $i + 1;
		}
		$this->current = $bitmap;
		$w1 = $target->width;
		$h1 = $target->height;
		$bitmap1 = new geometrize_bitmap_Bitmap();
		$bitmap1->width = $w1;
		$bitmap1->height = $h1;
		$this2 = (new _hx_array(array()));
		$this2->length = $w1 * $h1;
		$bitmap1->data = $this2;
		$i1 = 0;
		while($i1 < $bitmap1->data->length) {
			$bitmap1->data[$i1] = $backgroundColor;
			$i1 = $i1 + 1;
		}
		$this->buffer = $bitmap1;
		$this->score = geometrize_Core::differenceFull($target, $this->current);
	}}
	public $width;
	public $height;
	public $target;
	public $current;
	public $buffer;
	public $score;
	public function step($shapeTypes, $alpha, $n, $age) {
		$state = geometrize_Core::bestHillClimbState($shapeTypes, $alpha, $n, $age, $this->target, $this->current, $this->buffer, $this->score);
		$results = (new _hx_array(array($this->addShape($state->shape, $state->alpha))));
		return $results;
	}
	public function addShape($shape, $alpha) {
		if(!($shape !== null)) {
			throw new HException("FAIL: shape != null");
		}
		$_this = $this->current;
		$bitmap = new geometrize_bitmap_Bitmap();
		$bitmap->width = $_this->width;
		$bitmap->height = $_this->height;
		$this1 = (new _hx_array(array()));
		$this1->length = $_this->data->length;
		$bitmap->data = $this1;
		{
			$_g1 = 0;
			$_g = $_this->data->length;
			while($_g1 < $_g) {
				$_g1 = $_g1 + 1;
				$i = $_g1 - 1;
				$bitmap->data[$i] = $_this->data[$i];
				unset($i);
			}
		}
		$before = $bitmap;
		if (isset($this->current->errorCache)) {
			$before->errorCache = $this->current->errorCache;
		}
		$lines = $shape->rasterize();
		if (!isset($shape->color)) {
			$shape->color = geometrize_Core::computeColor($this->target, $this->current, $lines, $alpha);
		}
		geometrize_rasterizer_Rasterizer::drawLines($this->current, $shape->color, $lines);
		$this->score = geometrize_Core::differencePartial($this->target, $before, $this->current, $this->score, $lines);
		$score_normalization = $_this->width * $_this->height * 4 * 255;
		$result = _hx_anonymous(array("score" => $this->score / $score_normalization, "color" => $shape->color, "shape" => $shape));
		return $result;
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
	function __toString() { return 'geometrize.Model'; }
}
