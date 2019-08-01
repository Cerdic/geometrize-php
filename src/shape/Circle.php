<?php

namespace Cerdic\Geometrize\Shape;

use \Cerdic\Geometrize\Exporter\SvgExporter;

class Circle extends Ellipse {

	public function __construct($xBound, $yBound, $sizeFactor=1.0){
		parent::__construct($xBound, $yBound, $sizeFactor);
		$this->ry = $this->rx;
	}

	public function mutate(){
		$r = mt_rand(0, 1);
		switch ($r) {
			case 0:
				$this->x += mt_rand(-16, +16);
				$this->x = max(min($this->x, $this->xBound-1),0);
				$this->y += mt_rand(-16, +16);
				$this->y = max(min($this->y, $this->yBound-1),0);
				break;
				break;
			case 1:
				$this->rx += mt_rand(-16, +16);
				$this->rx = max(min($this->rx, $this->xBound-1),1);
				$this->ry = $this->rx;
				break;
		}

		// force to rasterize the new shape
		$this->lines = null;
	}

	public function getType(){
		return ShapeTypes::T_CIRCLE;
	}

	public function getRawShapeData(){
		return [
			$this->x,
			$this->y,
			$this->rx
		];
	}

	public function getSvgShapeData(){
		return "<circle cx=\"" . $this->x . "\" cy=\"" . $this->y . "\" r=\"" . $this->rx . "\" " . SvgExporter::$SVG_STYLE_HOOK . " />";
	}

}
