<?php

// Generated by Haxe 3.4.7
class geometrize_rasterizer_Scanline {
	public $y;
	public $x1;
	public $x2;

	public function __construct($y, $x1, $x2){
		$this->y = $y;
		$this->x1 = $x1;
		$this->x2 = $x2;
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

	/**
	 * Rescale the scanline
	 * @param float $xScale
	 * @param float $yScale
	 */
	public function rescale($xScale, $yScale) {
		$this->y = intval(round($this->y*$yScale));
		$this->x1 = intval(round($this->x1*$xScale));
		$this->x2 = intval(round($this->x2*$xScale));
	}

	static function trim(&$scanlines, $w, $h){
		if (!is_array($scanlines)){
			throw new HException("FAIL: scanlines != array");
		}
		$max = $w-1;
		if (!(0<=$max)){
			throw new HException("FAIL: min <= max");
		}

		foreach ($scanlines as $k=>&$line) {
			if (!geometrize_rasterizer_Scanline::trimHelper($line, $w, $h)) {
				unset($scanlines[$k]);
			}
		}
		/*
		// trim each scanline with the bounds
		$scanlines = array_map(function (&$line) use ($w,$h) { return geometrize_rasterizer_Scanline::trimHelper($line, $w, $h); }, $scanlines);
		// remove empty scanlines
		$scanlines = array_filter($scanlines);
		*/

		return $scanlines;
	}

	/**
	 * Trim a ScanLine
	 * @param geometrize_rasterizer_Scanline $line
	 * @param int $w
	 * @param int $h
	 * @return bool
	 * @throws HException
	 */
	static function trimHelper(&$line, $w, $h){
		if ($line->y<0 or $line->y>=$h) {
			return false;
		}
		if ($line->x1>=$w or $line->x2<0 or $line->x2<$line->x1) {
			return false;
		}

		$line->x1 = max($line->x1, 0);
		$line->x2 = min($line->x2, $w-1);

		return true;
	}

	function __toString(){
		return 'geometrize.rasterizer.Scanline';
	}
}
