<?php

namespace Cerdic\Geometrize\Shape;

interface Shape {
	public function rasterize();

	public function mutate();

	public function getSizeFactor();

	public function rescale($xBound, $yBound);

	public function getType();

	public function getRawShapeData();

	public function getSvgShapeData();
}
