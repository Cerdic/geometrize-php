<?php

namespace Cerdic\Geometrize\Shape;

use \Cerdic\Geometrize\Exporter\SvgExporter;
use \Cerdic\Geometrize\Rasterizer\Rasterizer;

class RotatedEllipse implements Shape {

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
		$this->x = mt_rand(0, $xBound-1);
		$this->y = mt_rand(0, $yBound-1);
		$this->rx = intval(mt_rand(1, $xBound >> 2) * $sizeFactor);
		$this->ry = intval(mt_rand(1, $yBound >> 2) * $sizeFactor);
		$this->angle = mt_rand(0, 359);
		$this->xBound = $xBound;
		$this->yBound = $yBound;
	}

	public function rasterize(){
		if (!$this->lines){
			$pointCount = 20;
			$points = [];
			$rads = $this->angle*(M_PI/180.0);
			$c = cos($rads);
			$s = sin($rads);

			for ($i = 0; $i<$pointCount; $i++) {
				$rot = 360.0/$pointCount*$i*(M_PI/180.0);
				$crx = $this->rx;
				$crx1 = $crx*cos($rot);
				$cry = $this->ry;
				$cry1 = $cry*sin($rot);
				$tx = intval(round($crx1*$c-$cry1*$s+$this->x));
				$ty = intval(round($crx1*$s+$cry1*$c+$this->y));
				$points[] = ["x" => $tx, "y" => $ty];
			}

			$this->lines = Rasterizer::scanlinesForPolygon($points, $this->xBound, $this->yBound);
		}
		return $this->lines;
	}

	public function mutate(){
		$r = mt_rand(0, 3);
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

			case 3:
				$this->angle += mt_rand(-4, +4);
				$this->angle = (360 + $this->angle) % 360;
				break;
		}

		// force to rasterize the new shape
		$this->lines = null;

	}

	/**
	 * Get an approximative size ratio of the shape vs the bounds
	 * @return float|int
	 */
	public function getSizeFactor(){
		return $this->rx / $this->xBound + $this->ry / $this->yBound;
	}


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

	public function getType(){
		return ShapeTypes::T_ROTATED_ELLIPSE;
	}

	/**
	 * @return array
	 */
	public function getRawShapeData(){
		return [
			$this->x,
			$this->y,
			$this->rx,
			$this->ry,
			$this->angle
		];
	}

	/**
	 * @return string
	 */
	public function getSvgShapeData(){
		$s = "<g transform=\"rotate(" . $this->angle . " " . $this->x . " " . $this->y . ")\">";
		$s .= "<ellipse cx=\"" . $this->x . "\" cy=\"" . $this->y . "\" rx=\"" . $this->rx . "\" ry=\"" . $this->ry . "\" " . SvgExporter::$SVG_STYLE_HOOK . " />";
		$s .= "</g>";
		return $s;
	}

}
