<?php

/**
 * PHP version of https://github.com/Tw1ddle/geometrize-haxe/commit/e6ed1ab8c3867ac0da5bfea25ee4e7204ad286be
 * Based on PHP code generated with Haxe on this base version
 * + manual optimization&refactoring
 *
 */

if(version_compare(PHP_VERSION, '5.4.0', '<')) {
    exit('Your current PHP version is: ' . PHP_VERSION . '. Geometrize expected version 5.4.0 or later');
}


require_once __DIR__ . '/src/Bitmap.php';

require_once __DIR__ . '/src/bitmap/DominantColours.php';
require_once __DIR__ . '/src/rasterizer/Rasterizer.php';


require_once __DIR__ . '/src/shape/ShapeTypes.php';
require_once __DIR__ . '/src/shape/Shape.php';
require_once __DIR__ . '/src/shape/ShapeFactory.php';

require_once __DIR__ . '/src/shape/Ellipse.php';
require_once __DIR__ . '/src/shape/QuadraticBezier.php';
require_once __DIR__ . '/src/shape/RotatedEllipse.php';
require_once __DIR__ . '/src/shape/Rectangle.php';
require_once __DIR__ . '/src/shape/RotatedRectangle.php';
require_once __DIR__ . '/src/shape/Triangle.php';
require_once __DIR__ . '/src/shape/Circle.php';
require_once __DIR__ . '/src/shape/Line.php';


require_once __DIR__ . '/src/exporter/SvgExporter.php';

require_once __DIR__ . '/src/State.php';
require_once __DIR__ . '/src/Model.php';
require_once __DIR__ . '/src/Core.php';
require_once __DIR__ . '/src/ImageRunner.php';
require_once __DIR__ . '/src/Version.php';
