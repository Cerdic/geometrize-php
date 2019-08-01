<?php

namespace Cerdic\Geometrize\Exporter;

use \Cerdic\Geometrize\Shape\ShapeTypes;

class SvgExporter {
	public function __construct(){
	}

	static $SVG_STYLE_HOOK = "::svg_style_hook::";

	/**
	 * @param array $shapes
	 * @param int $boxWidth
	 * @param int $boxHeight
	 * @param int $imageWidth
	 * @param int $imageHeight
	 * @return string
	 */
	static function export($shapes, $boxWidth, $boxHeight, $imageWidth=0, $imageHeight=0){
		$out = SvgExporter::getSvgPrelude();
		$out .= SvgExporter::getSvgNodeOpen($boxWidth, $boxHeight, $imageWidth, $imageHeight);
		$out .= SvgExporter::exportShapes($shapes);
		$out .= SvgExporter::getSvgNodeClose();
		return $out;
	}

	/**
	 * @param array $shapes
	 * @return string
	 */
	static function exportShapes($shapes){
		$out = [];
		foreach ($shapes as $shape) {
			$out[] = SvgExporter::exportShape($shape);
		}
		$out = implode("\x0A", $out);
		return $out;
	}

	/**
	 * @param \Cerdic\Geometrize\Shape\Shape $shape
	 * @return string
	 */
	static function exportShape($shape){
		$s = $shape->getSvgShapeData();
		$sub = SvgExporter::$SVG_STYLE_HOOK;
		$by = SvgExporter::stylesForShape($shape);
		if ($sub===""){
			return implode(str_split($s), $by);
		} else {
			return str_replace($sub, $by, $s);
		}
	}

	static public function exportPolygon($points) {
		return SvgExporter::exportLines($points, true);
	}

	/**
	 * @param array $points
	 *   each element is a ['x'=>int, 'y'=>int] array
	 * @param bool $closed
	 * @return string
	 */
	static public function exportLines($points, $closed = false) {
		$s1 = "<path d=\"M";

		$point = array_shift($points);
		$s1 .= $point['x'] . "," . $point['y'];

		$prevPoint = $point;
		foreach ($points as $point) {
			// find the shortest command to draw a line to this new point
			$dx = $point['x']-$prevPoint['x'];
			$dy = $point['y']-$prevPoint['y'];
			if ($dx === 0) {
				$pa = "V".$point['y'];
				$pr = "v".$dy;
			} elseif ($dy === 0) {
				$pa = "H".$point['x'];
				$pr = "h".$dx;
			}
			else {
				$pa = "L" . $point['x'] . "," . $point['y'];
				$pr = "l" . $dx . ',' . $dy;
			}
			if (strlen($pr)<strlen($pa)) {
				$s1 .= $pr;
			}
			else {
				$s1 .= $pa;
			}
			$prevPoint = $point;
		}
		if ($closed) {
			$s1 .= "z";
		}
		$s1 .= "\" " . SvgExporter::$SVG_STYLE_HOOK . "/>";
		return $s1;
	}

	/**
	 * @return string
	 */
	static function getSvgPrelude(){
		return "<?xml version=\"1.0\"?>\x0A";
	}

	/**
	 * @param int $boxWidth
	 * @param int $boxHeight
	 * @param int $imageWidth
	 * @param int $imageHeight
	 * @return string
	 */
	static function getSvgNodeOpen($boxWidth, $boxHeight, $imageWidth=0, $imageHeight=0){
		$viewBox = "0 0 " . intval($boxWidth -1) . " " . intval($boxHeight -1);
		$attrWH = "";
		if ($imageWidth = intval($imageWidth)) {
			$attrWH .= " width=\"$imageWidth\"";
		}
		if ($imageHeight = intval($imageHeight)) {
			$attrWH .= " height=\"$imageHeight\"";
		}
		return "<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.2\" viewBox=\"$viewBox\"$attrWH>\x0A";
	}

	/**
	 * @return string
	 */
	static function getSvgNodeClose(){
		return "</svg>";
	}

	/**
	 * @param \Cerdic\Geometrize\Shape\Shape $shape
	 * @return string
	 */
	static function stylesForShape($shape){
		$style = "";
		switch ($shape->getType()) {
			case ShapeTypes::T_LINE:
				$style = SvgExporter::strokeForColor($shape->color);
				$style .= SvgExporter::strokeOpacityForAlpha($shape->color & 255);
				break;
			case ShapeTypes::T_QUADRATIC_BEZIER:
				$style = SvgExporter::strokeForColor($shape->color) . " fill=\"none\" ";
				$style .= SvgExporter::strokeOpacityForAlpha($shape->color & 255);
				break;
			default:
				$style = SvgExporter::fillForColor($shape->color) . " ";
				$style .= SvgExporter::fillOpacityForAlpha($shape->color & 255);
				break;
		}
		return $style ;
	}

	/**
	 * @param int $color
	 * @return string
	 */
	static function rgbForColor($color){
		return "rgb(" . ($color >> 24 & 255) . "," . ($color >> 16 & 255) . "," . ($color >> 8 & 255) . ")";
	}

	/**
	 * @param $color
	 * @return string
	 */
	static function hexaForColor($color) {
		$red = str_pad(dechex($color >> 24 & 255), 2, "0", STR_PAD_LEFT);
		$green = str_pad(dechex($color >> 16 & 255), 2, "0", STR_PAD_LEFT);
		$blue = str_pad(dechex($color >> 8 & 255), 2, "0", STR_PAD_LEFT);
		return "#".$red.$green.$blue;
	}

	/**
	 * @param int $color
	 * @return string
	 */
	static function strokeForColor($color){
		return "stroke=\"" . SvgExporter::hexaForColor($color) . "\"";
	}

	/**
	 * @param int $color
	 * @return string
	 */
	static function fillForColor($color){
		return "fill=\"" . SvgExporter::hexaForColor($color) . "\"";
	}

	/**
	 * @param int $alpha
	 * @return string
	 */
	static function fillOpacityForAlpha($alpha){
		if ($alpha === 255) {
			return "";
		}
		return "fill-opacity=\"" . ($alpha/255.0) . "\"";
	}

	/**
	 * @param int $alpha
	 * @return string
	 */
	static function strokeOpacityForAlpha($alpha){
		if ($alpha === 255) {
			return "";
		}
		return "stroke-opacity=\"" . ($alpha/255.0)  . "\"";
	}

}
