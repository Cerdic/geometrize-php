<?php

namespace Cerdic\Geometrize\Shape;

use \Cerdic\Geometrize\Exporter\SvgExporter;
use \Cerdic\Geometrize\Rasterizer\Rasterizer;


class RotatedRectangle implements Shape {

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
	protected $angle;

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

	public function __construct($xBound, $yBound, $sizeFactor=1.0){
		$this->x1 = mt_rand(0, $xBound-1);
		$this->y1 = mt_rand(0, $yBound-1);

		$this->x2 = $this->x1 + intval(mt_rand(0, +$xBound>>2) * $sizeFactor);
		$this->x2 = min($this->x2, $xBound-1);

		$this->y2 = $this->y1 + intval(mt_rand(0, +$yBound>>2) * $sizeFactor);
		$this->y2 = min($this->y2, $yBound-1);

		$this->angle = mt_rand(0, 359);

		$this->xBound = $xBound;
		$this->yBound = $yBound;
	}

	/**
	 * Rasterize the shape
	 * @return array
	 * @throws \Exception
	 */
	public function rasterize(){
		if (!$this->lines){
			$points = $this->getCornerPoints();
			$this->lines = Rasterizer::scanlinesForPolygon($points, $this->xBound, $this->yBound);
		}
		return $this->lines;
	}

	/**
	 * Mutate the shape
	 * @throws \Exception
	 */
	public function mutate(){
		$r = mt_rand(0, 2);
		switch ($r) {
			case 0:
				$this->x1 += mt_rand(-16, +16);
				$this->x1 = max(min($this->x1, $this->xBound-1),0);
				$this->y1 += mt_rand(-16, +16);
				$this->y1 = max(min($this->y1, $this->yBound-1),0);
				break;
			case 1:
				$this->x2 += mt_rand(-16, +16);
				$this->x2 = max(min($this->x2, $this->xBound-1),0);
				$this->y2 += mt_rand(-16, +16);
				$this->y2 = max(min($this->y2, $this->yBound-1),0);
				break;
			case 2:
				$this->angle += mt_rand(-4, +4);
				$this->angle = (360 + $this->angle) % 360;
				break;
		}

		// force to rasterize the new shape
		$this->lines = null;
	}

	public function getSizeFactor(){

		$dx = abs($this->x1-$this->x2);
		$dy = abs($this->y1-$this->y2);

		return $dx / $this->xBound + $dy / $this->yBound;
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

		// need to rasterize again
		$this->lines = null;
	}

	public function getType(){
		return ShapeTypes::T_ROTATED_RECTANGLE;
	}

	/**
	 * @return array
	 */
	public function getRawShapeData(){
		if ($this->x1<$this->x2){
			$xfirst = $this->x1;
			$xsecond = $this->x2;
		} else {
			$xfirst = $this->x2;
			$xsecond = $this->x1;
		}
		if ($this->y1<$this->y2){
			$yfirst = $this->y1;
			$ysecond = $this->y2;
		} else {
			$yfirst = $this->y2;
			$ysecond = $this->y1;
		}

		return [
			$xfirst,
			$yfirst,
			$xsecond,
			$ysecond,
			$this->angle
		];
	}

	/**
	 * @return string
	 */
	public function getSvgShapeData(){
		$points = $this->getCornerPoints();
		return SvgExporter::exportPolygon($points);
	}

	/**
	 * @return array
	 */
	public function getCornerPoints(){
		list($xm1, $ym1, $xm2, $ym2, $angle) = $this->getRawShapeData();

		$cx = intval(($xm1+$xm2)/2);
		$cy = intval(($ym1+$ym2)/2);
		$ox1 = $xm1-$cx;
		$ox2 = $xm2-$cx;
		$oy1 = $ym1-$cy;
		$oy2 = $ym2-$cy;
		$rads = $this->angle * M_PI/180.0;
		$c = cos($rads);
		$s = sin($rads);

		$ulx = intval(round($ox1*$c-$oy1*$s+$cx));
		$uly = intval(round($ox1*$s+$oy1*$c+$cy));
		$blx = intval(round($ox1*$c-$oy2*$s+$cx));
		$bly = intval(round($ox1*$s+$oy2*$c+$cy));
		$urx = intval(round($ox2*$c-$oy1*$s+$cx));
		$ury = intval(round($ox2*$s+$oy1*$c+$cy));
		$brx = intval(round($ox2*$c-$oy2*$s+$cx));
		$bry = intval(round($ox2*$s+$oy2*$c+$cy));

		$corners = [
			["x" => $ulx, "y" => $uly],
			["x" => $urx, "y" => $ury],
			["x" => $brx, "y" => $bry],
			["x" => $blx, "y" => $bly]
		];
		return $corners;
	}

}
