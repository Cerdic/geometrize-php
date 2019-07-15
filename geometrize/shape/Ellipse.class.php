<?php

// Generated by Haxe 3.4.7
class geometrize_shape_Ellipse implements geometrize_shape_Shape {

	/**
	 * @var int
	 */
	protected $x;

	/**
	 * @var int
	 */
	protected $y;

	/**
	 * @var int
	 */
	protected $rx;

	/**
	 * @var int
	 */
	protected $ry;

	/**
	 * @var int
	 */
	protected $xBound;

	/**
	 * @var int
	 */
	protected $yBound;

	/**
	 * @var int
	 */
	public $color;

	/**
	 * Rasterized lines
	 * @var null|array
	 */
	protected $lines = null;

	public function __construct($xBound, $yBound){
		$this->x = mt_rand(0, $xBound-1);
		$this->y = mt_rand(0, $yBound-1);
		$this->rx = mt_rand(1, 32);
		$this->ry = mt_rand(1, 32);
		$this->xBound = $xBound;
		$this->yBound = $yBound;
	}

	/**
	 * Rasterize the shape
	 * @return array
	 * @throws HException
	 */
	public function rasterize(){
		if (!$this->lines){
			$this->lines = [];

			$aspect = $this->rx/$this->ry;
			$w = $this->xBound;
			$h = $this->yBound;

			for ($dy = 0; $dy<$this->ry; $dy++) {
				$y1 = $this->y-$dy;
				$y2 = $this->y+$dy;

				$s = null;
				if ($y1>=0 or $y2<$h){
					$s = intval(sqrt($this->ry*$this->ry-$dy*$dy)*$aspect);
					$x1 = max($this->x-$s, 0);
					$x2 = min($this->x+$s, $w-1);
				}

				if ($y1>=0){
					$this->lines[] = new geometrize_rasterizer_Scanline($y1, $x1, $x2);
				}
				if ($y2<$h){
					$this->lines[] = new geometrize_rasterizer_Scanline($y2, $x1, $x2);
				}
			}
		}
		return $this->lines;
	}

	/**
	 * Mutate the shape
	 * @throws HException
	 */
	public function mutate(){
		$r = mt_rand(0, 2);
		switch ($r) {
			case 0:
				$this->x += mt_rand(-16, +16);
				$this->x = max(min($this->x, $this->xBound-1),0);
				$this->y += mt_rand(-16, +16);
				$this->y = max(min($this->y, $this->yBound-1),0);
				break;

			case 1:
				$this->rx += mt_rand(-16, +16);
				$this->rx = max(min($this->rx, $this->xBound-1),1);
				break;
			case 2:
				$this->ry += mt_rand(-16, +16);
				$this->ry = max(min($this->ry, $this->yBound-1),1);
				break;
		}

		// force to rasterize the new shape
		$this->lines = null;

	}

	/**
	 * @param int $xBound
	 * @param int $yBound
	 */
	public function rescale($xBound, $yBound){
		$xScale = ($xBound-1) / ($this->xBound-1);
		$yScale = ($yBound-1) / ($this->yBound-1);
		$this->xBound = $xBound;
		$this->yBound = $yBound;
		$this->x = intval(round($this->x*$xScale));
		$this->y = intval(round($this->y*$yScale));
		$this->rx = intval(round($this->rx*$xScale));
		$this->ry = intval(round($this->ry*$yScale));

		// need to rasterize again
		$this->lines = null;
	}

	public function __clone() {
		if ($this->lines) {
			foreach ($this->lines as $k=>&$line) {
				$this->lines[$k] = clone $line;
			}
		}
  }

	public function getType(){
		return geometrize_shape_ShapeTypes::T_ELLIPSE;
	}

	/**
	 * @return array
	 */
	public function getRawShapeData(){
		return [
			$this->x,
			$this->y,
			$this->rx,
			$this->ry
		];
	}

	public function getSvgShapeData(){
		return "<ellipse cx=\"" . $this->x . "\" cy=\"" . $this->y . "\" rx=\"" . $this->rx . "\" ry=\"" . $this->ry . "\" " . geometrize_exporter_SvgExporter::$SVG_STYLE_HOOK . " />";
	}

	public function __call($m, $a){
		if (isset($this->$m) && is_callable($this->$m)){
			return call_user_func_array($this->$m, $a);
		} else {
			if (isset($this->__dynamics[$m]) && is_callable($this->__dynamics[$m])){
				return call_user_func_array($this->__dynamics[$m], $a);
			} else {
				if ('toString'==$m){
					return $this->__toString();
				} else {
					throw new HException('Unable to call <' . $m . '>');
				}
			}
		}
	}

	function __toString(){
		return 'geometrize.shape.Ellipse';
	}
}
