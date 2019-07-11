<?php

// Generated by Haxe 3.4.7
class geometrize_shape_Triangle implements geometrize_shape_Shape {

	/**
	 * @var int
	 */
	protected $x1;

	/**
	 * @var int
	 */
	protected $y1;

	/**
	 * @var int
	 */
	protected $x2;

	/**
	 * @var int
	 */
	protected $y2;

	/**
	 * @var int
	 */
	protected $x3;

	/**
	 * @var int
	 */
	protected $y3;

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
		$this->x1 = mt_rand(0, $xBound-1);
		$this->y1 = mt_rand(0, $yBound-1);
		$this->x2 = $this->x1 + mt_rand(-16, +16);
		$this->y2 = $this->y1 + mt_rand(-16, +16);
		$this->x3 = $this->x1 + mt_rand(-16, +16);
		$this->y3 = $this->y1 + mt_rand(-16, +16);

		$this->xBound = $xBound;
		$this->yBound = $yBound;
	}

	/**
	 * Rasterize the shape
	 * @return array
	 * @throws HException
	 */
	public function rasterize(){
		if (!$this->lines) {
			$points = [
				["x" => $this->x1, "y" => $this->y1],
				["x" => $this->x2, "y" => $this->y2],
				["x" => $this->x3, "y" => $this->y3],
			];
			$this->lines = geometrize_rasterizer_Rasterizer::scanlinesForPolygon($points, $this->xBound, $this->yBound);
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
				$this->x1 += mt_rand(-16, +16);
				#$this->x1 = max(min($this->x1, $this->xBound-1),0);
				$this->y1 += mt_rand(-16, +16);
				#$this->y1 = max(min($this->y1, $this->yBound-1),0);
				break;

			case 1:
				$this->x2 += mt_rand(-16, +16);
				#$this->x2 = max(min($this->x2, $this->xBound-1),0);
				$this->y2 +=  mt_rand(-16, +16);
				#$this->y2 = max(min($this->y2, $this->yBound-1),0);
				break;

			case 2:
				$this->x3 +=  mt_rand(-16, +16);
				#$this->x3 = max(min($this->x3, $this->xBound-1),0);
				$this->y3 += mt_rand(-16, +16);
				#$this->y3 = max(min($this->y3, $this->yBound-1),0);
				break;
		}

		// force to rasterize the new shape
		$this->lines = null;
	}

	/**
	 * rescale the shape to new bound dimensions
	 * @param $xBound
	 * @param $yBound
	 */
	public function rescale($xBound, $yBound){
		$xScale = ($xBound-1) / ($this->xBound-1);
		$yScale = ($yBound-1) / ($this->yBound-1);
		$this->xBound = $xBound;
		$this->yBound = $yBound;
		$this->x1 = intval(round($this->x1*$xScale));
		$this->y1 = intval(round($this->y1*$yScale));
		$this->x2 = intval(round($this->x2*$xScale));
		$this->y2 = intval(round($this->y2*$yScale));
		$this->x3 = intval(round($this->x3*$xScale));
		$this->y3 = intval(round($this->y3*$yScale));

		if ($this->lines) {
			foreach ($this->lines as &$line) {
				$line->rescale($xScale, $yScale);
			}
		}
	}

	public function hclone(){
		return clone $this;
	}

	public function __clone() {
		if ($this->lines) {
			foreach ($this->lines as $k=>&$line) {
				$this->lines[$k] = clone $line;
			}
		}
  }

	public function getType(){
		return geometrize_shape_ShapeTypes::T_TRIANGLE;
	}

	/**
	 * @return array
	 */
	public function getRawShapeData(){
		return [
			$this->x1,
			$this->y1,
			$this->x2,
			$this->y2,
			$this->x3,
			$this->y3
		];
	}

	public function getSvgShapeData(){
		$points = [
			["x" => $this->x1, "y" => $this->y1],
			["x" => $this->x2, "y" => $this->y2],
			["x" => $this->x3, "y" => $this->y3],
		];
		return geometrize_exporter_SvgExporter::exportPolygon($points);
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
		return 'geometrize.shape.Triangle';
	}
}
