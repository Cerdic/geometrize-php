<?php

// Generated by Haxe 3.4.7
class geometrize_shape_Line implements geometrize_shape_Shape {
	public $x1;
	public $y1;
	public $x2;
	public $y2;
	public $xBound;
	public $yBound;
	public $color;

	public function __construct($xBound, $yBound){
		$this->x1 = mt_rand(0, $xBound-1);
		$this->y1 = mt_rand(0, $yBound-1);
		$value = $this->x1;
		$value1 = $value+mt_rand(1, 32);
		if (!(0<=$xBound)){
			throw new HException("FAIL: min <= max");
		}
		$tmp = null;
		if ($value1<0){
			$tmp = 0;
		} else {
			if ($value1>$xBound){
				$tmp = $xBound;
			} else {
				$tmp = $value1;
			}
		}
		$this->x2 = $tmp;
		$value2 = $this->y1;
		$value3 = $value2+mt_rand(1, 32);
		if (!(0<=$yBound)){
			throw new HException("FAIL: min <= max");
		}
		$tmp1 = null;
		if ($value3<0){
			$tmp1 = 0;
		} else {
			if ($value3>$yBound){
				$tmp1 = $yBound;
			} else {
				$tmp1 = $value3;
			}
		}
		$this->y2 = $tmp1;
		$this->xBound = $xBound;
		$this->yBound = $yBound;
	}

	/**
	 * @return array
	 * @throws HException
	 */
	public function rasterize(){
		$points = [
			['x' => $this->x1, 'y' => $this->y1],
			['x' => $this->x2, 'y' => $this->y2]
		];

		$lines = geometrize_rasterizer_Rasterizer::scanlinesForPath($points);
		return geometrize_rasterizer_Scanline::trim($lines, $this->xBound, $this->yBound);
	}

	public function mutate(){
		$r = mt_rand(0, 3); // TODO : fixme 0,1
		switch ($r) {
			case 0:
				{
					$value = $this->x1;
					if (!true){
						throw new HException("FAIL: lower <= upper");
					}
					$value1 = $value+mt_rand(-16, +16);
					$max = $this->xBound-1;
					if (!(0<=$max)){
						throw new HException("FAIL: min <= max");
					}
					$tmp = null;
					if ($value1<0){
						$tmp = 0;
					} else {
						if ($value1>$max){
							$tmp = $max;
						} else {
							$tmp = $value1;
						}
					}
					$this->x1 = $tmp;
					$value2 = $this->y1;
					if (!true){
						throw new HException("FAIL: lower <= upper");
					}
					$value3 = $value2+mt_rand(-16, +16);
					$max1 = $this->yBound-1;
					if (!(0<=$max1)){
						throw new HException("FAIL: min <= max");
					}
					$tmp1 = null;
					if ($value3<0){
						$tmp1 = 0;
					} else {
						if ($value3>$max1){
							$tmp1 = $max1;
						} else {
							$tmp1 = $value3;
						}
					}
					$this->y1 = $tmp1;
				}
				break;
			case 1:
				{
					$value4 = $this->x2;
					if (!true){
						throw new HException("FAIL: lower <= upper");
					}
					$value5 = $value4+mt_rand(-16, +16);
					$max2 = $this->xBound-1;
					if (!(0<=$max2)){
						throw new HException("FAIL: min <= max");
					}
					$tmp2 = null;
					if ($value5<0){
						$tmp2 = 0;
					} else {
						if ($value5>$max2){
							$tmp2 = $max2;
						} else {
							$tmp2 = $value5;
						}
					}
					$this->x2 = $tmp2;
					$value6 = $this->y2;
					if (!true){
						throw new HException("FAIL: lower <= upper");
					}
					$value7 = $value6+mt_rand(-16, +16);
					$max3 = $this->yBound-1;
					if (!(0<=$max3)){
						throw new HException("FAIL: min <= max");
					}
					$tmp3 = null;
					if ($value7<0){
						$tmp3 = 0;
					} else {
						if ($value7>$max3){
							$tmp3 = $max3;
						} else {
							$tmp3 = $value7;
						}
					}
					$this->y2 = $tmp3;
				}
				break;
		}
	}

	public function rescale($xBound, $yBound){
		$xScale = ($xBound-1) / ($this->xBound-1);
		$yScale = ($yBound-1) / ($this->yBound-1);
		$this->xBound = $xBound;
		$this->yBound = $yBound;
		$this->x1 = intval(round($this->x1*$xScale));
		$this->y1 = intval(round($this->y1*$yScale));
		$this->x2 = intval(round($this->x2*$xScale));
		$this->y2 = intval(round($this->y2*$yScale));
	}

	public function hclone(){
		$line = new geometrize_shape_Line($this->xBound, $this->yBound);
		$line->x1 = $this->x1;
		$line->y1 = $this->y1;
		$line->x2 = $this->x2;
		$line->y2 = $this->y2;
		$line->color = $this->color;

		return $line;
	}

	public function getType(){
		return geometrize_shape_ShapeTypes::T_LINE;
	}

	/**
	 * @return array
	 */
	public function getRawShapeData(){
		return [
			$this->x1,
			$this->y1,
			$this->x2,
			$this->y2
		];
	}

	/**
	 * @return string
	 */
	public function getSvgShapeData(){
		return "<line x1=\"" . $this->x1 . "\" y1=\"" . $this->y1 . "\" x2=\"" . $this->x2 . "\" y2=\"" . $this->y2 . "\" " . geometrize_exporter_SvgExporter::$SVG_STYLE_HOOK . " />";
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
		return 'geometrize.shape.Line';
	}
}
