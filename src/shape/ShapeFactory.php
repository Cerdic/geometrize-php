<?php

namespace Cerdic\Geometrize\Shape;

class ShapeFactory {

	/**
	 * Create a random shape from a given type
	 * @param int $type
	 * @param int $xBound
	 * @param int $yBound
	 * @param float $shapeSizeFactor
	 * @return \Cerdic\Geometrize\Shape\Shape
	 */
	static function create($type, $xBound, $yBound, $shapeSizeFactor=1.0){
		switch ($type) {
			case ShapeTypes::T_RECTANGLE:
				return new Rectangle($xBound, $yBound);
				break;
			case ShapeTypes::T_ROTATED_RECTANGLE:
				return new RotatedRectangle($xBound, $yBound);
				break;
			case ShapeTypes::T_TRIANGLE:
				return new Triangle($xBound, $yBound, $shapeSizeFactor);
				break;
			case ShapeTypes::T_ELLIPSE:
				return new Ellipse($xBound, $yBound);
				break;
			case ShapeTypes::T_ROTATED_ELLIPSE:
				return new RotatedEllipse($xBound, $yBound);
				break;
			case ShapeTypes::T_CIRCLE:
				return new Circle($xBound, $yBound);
				break;
			case ShapeTypes::T_LINE:
				return new Line($xBound, $yBound);
				break;
			case ShapeTypes::T_QUADRATIC_BEZIER:
				return new QuadraticBezier($xBound, $yBound);
				break;
		}
	}

	/**
	 * Create any random type of a shape in known shape types
	 *
	 * @param int $xBound
	 * @param int $yBound
	 * @param float $shapeSizeFactor
	 * @return \Cerdic\Geometrize\Shape\Shape
	 * @throws \Exception
	 */
	static function randomShape($xBound, $yBound, $shapeSizeFactor=1.0){
		$a = [
			ShapeTypes::T_RECTANGLE,
			ShapeTypes::T_ROTATED_RECTANGLE,
			ShapeTypes::T_TRIANGLE,
			ShapeTypes::T_ELLIPSE,
			ShapeTypes::T_ROTATED_ELLIPSE,
			ShapeTypes::T_CIRCLE,
			ShapeTypes::T_LINE,
			ShapeTypes::T_QUADRATIC_BEZIER,
		];
		return ShapeFactory::randomShapeOf($a, $xBound, $yBound, $shapeSizeFactor);
	}

	/**
	 * Create a random shape in a given list of possioble types
	 * @param array $types
	 * @param int $xBound
	 * @param int $yBound
	 * @param float $shapeSizeFactor
	 * @return \Cerdic\Geometrize\Shape\Shape
	 * @throws \Exception
	 */
	static function randomShapeOf($types, $xBound, $yBound, $shapeSizeFactor=1.0){
		if (!is_array($types) || !count($types)){
			throw new \Exception("FAIL: types != null && count(types) > 0");
		}
		return ShapeFactory::create($types[mt_rand(0, count($types)-1)], $xBound, $yBound, $shapeSizeFactor);
	}

}
