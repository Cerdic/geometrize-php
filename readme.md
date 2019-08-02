# GeometrizePHP

This is a PHP Port, optimization and refactoring of the https://github.com/Tw1ddle/geometrize-haxe
The initial code has been auto generated with Haxe and then cleaned and optimized for performance and readability purposes

# Usage

```
use \Cerdic\Geometrize\Shape\ShapeTypes;
use \Cerdic\Geometrize\Bitmap;
use \Cerdic\Geometrize\ImageRunner;

include_once "geometrize.init.php";

$geometrize_options = [
	"shapeTypes" => [ShapeTypes::T_TRIANGLE],
	"alpha" => 255,
	"candidateShapesPerStep" => 100,
	"shapeMutationsPerStep" => 50,
];


$bitmap = Bitmap::createFromImageFile($imageFile);
$runner = new ImageRunner($bitmap);

$nb_shapes = 100;
$score = $runner->steps($geometrize_options, $nb_shapes);

$svg = $runner->exportToSVG();
file_put_contents($fileSVG, $svg);

```

### Background Color

Not setting any background Color, Geometrize will determine the dominant color and use it as a background.

You can also remove any background color when intializing the Runner:
```
$runner = new ImageRunner($bitmap, false);
```

Or fix an arbitrary background color:

```
// set a background color
$red = 127;
$green = 127;
$blue = 127;
$alpha = 255;
$background = ($red << 24) + ($green << 16) + ($blue << 8) + $alpha;
$runner = new ImageRunner($bitmap, $background);
```

## Performance considerations

For performance purposes you should not operate directly on the full-sized image but on a thumbnail, 
like 128px, 256px or 512px wide depending on the quality of the rendering you want:

```
// TODO create a thumbnail and store it into $imageFileTumbnail
$bitmap = Bitmap::createFromImageFile($imageFileTumbnail);
$runner = new ImageRunner($bitmap, $background);

$nb_shapes = 100;
$score = $runner->steps($geometrize_options, $nb_shapes);

// export rescaling the SVG to the original Image size
$svg = $runner->exportToSVG($originalWidth, $originalHeight);

```


