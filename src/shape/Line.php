<?php

namespace Cerdic\Geometrize\Shape;

use \Cerdic\Geometrize\Exporter\SvgExporter;
use \Cerdic\Geometrize\Rasterizer\Rasterizer;

class Line extends Rectangle {

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function rasterize(){
		if (!$this->lines){
			list($xm1, $ym1, $xm2, $ym2) = $this->getRawShapeData();

			$this->lines = [];
			if ($xm2>$xm1 or $ym2>$ym1){
				$points = [
					['x' => $xm1, 'y' => $ym1],
					['x' => $xm2, 'y' => $ym2]
				];
				$this->lines = Rasterizer::scanlinesForPath($points, $this->xBound, $this->yBound);
			}
		}

		return $this->lines;
	}

	public function getType(){
		return ShapeTypes::T_LINE;
	}

	/**
	 * @return string
	 */
	public function getSvgShapeData(){
		list($xm1, $ym1, $xm2, $ym2) = $this->getRawShapeData();
		$points = [
			['x' => $xm1, 'y' => $ym1],
			['x' => $xm2, 'y' => $ym2]
		];
		return SvgExporter::exportLines($points);
	}

}
