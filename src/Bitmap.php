<?php

namespace Cerdic\Geometrize;

class Bitmap {
	/**
	 * @var int
	 */
	public $width;

	/**
	 * @var int
	 */
	public $height;

	/**
	 * @var array
	 */
	public $data;

	/**
	 * @var array
	 */
	public $errorCache;

	public function __construct(){
		$this->data = [];
		$this->errorCache = [];
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @return int
	 */
	public function getPixel($x, $y){
		return isset($this->data[$y][$x]) ? $this->data[$y][$x] : 255;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $color
	 */
	public function setPixel($x, $y, $color){
		$this->data[$y][$x] = $color;
	}

	/**
	 * @return int
	 */
	public function length() {
		return $this->width * $this->height;
	}

	/**
	 * @param int $color
	 */
	public function fill($color){
		for ($y=0;$y<$this->height;$y++) {
			for ($x=0;$x<$this->width;$x++) {
				$this->data[$y][$x] = $color;
			}
		}
	}

	/**
	 * @param string $file
	 * @param string $extension
	 * @param int|null $quality
	 * @return bool
	 */
	public function exportToImageFile($file, $extension, $quality = null) {
		$extension = strtolower($extension);
		if (!in_array($extension, ['gif', 'png', 'jpg'])) {
			return false;
		}

		$img = imagecreatetruecolor($this->width, $this->height);
		for($x = 0; $x<$this->width; $x++) {
			for($y = 0; $y<$this->height; $y++){
				$color = $this->getPixel($x, $y);
				$c = Bitmap::colorToRGBAArray($color);
				$color = imagecolorallocatealpha($img, $c['red'], $c['green'], $c['blue'], $c['alpha']);
				imagesetpixel($img, $x, $y, $color);
			}
		}

		$tmp_file = $file . ".tmp";
		$res = false;
		switch ($extension) {
			case 'png':
				$res = imagepng($img, $tmp_file);
				break;
			case 'gif':
				$res = imagegif($img, $tmp_file);
				break;
			case 'jpg':
				// Enable interlancing
				imageinterlace($img, true);
				$res = imagejpeg($img, $tmp_file, is_null($quality) ? 85 : $quality);
				break;
		}

		if (!$res || !file_exists($tmp_file)) {
			return false;
		}

		$size = getimagesize($tmp_file);
		if ($size[0]<1) {
			@unlink($tmp_file);
			return false;
		}

		@rename($tmp_file, $file);
		return true;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * @param int $color
	 * @return \Cerdic\Geometrize\Bitmap
	 */
	public static function create($w, $h, $color){
		$bitmap = new Bitmap();
		$bitmap->width = $w;
		$bitmap->height = $h;
		$bitmap->fill($color);
		return $bitmap;
	}

	/**
	 * @param string $file
	 * @return \Cerdic\Geometrize\Bitmap
	 */
	public static function createFromImageFile($file){
		list($w, $h) = getimagesize($file);
		$image = imagecreatefromstring(file_get_contents($file));

		$bitmap = new Bitmap();
		$bitmap->width = $w;
		$bitmap->height = $h;

		for ($y = 0; $y<$h; $y++){
			for ($x = 0; $x<$w; $x++){
				// get a color
				$color_index = imagecolorat($image, $x, $y);
				// make it human readable
				$c = imagecolorsforindex($image, $color_index);
				$bitmap->data[$y][$x] = Bitmap::colorFromRGBAArray($c);
			}
		}

		return $bitmap;
	}

	/**
	 * Convert color from HTML RGBA to Geometrize color system
	 * @param array $c
	 * @return int
	 */
	public static function colorFromRGBAArray($c){
		if (isset($c['alpha'])){
			// in RGBA 0 = opaque, 127 = transparent
			// in geometrize 0 = transparent, 255 = opaque
			$c['alpha'] = round((127-$c['alpha'])*255/127);
		} else {
			$c['alpha'] = 255;
		}
		$color = ($c['red'] << 24) + ($c['green'] << 16) + ($c['blue'] << 8) + $c['alpha'];
		return $color;
	}

	/**
	 * Convert color from Geometrize color system to HTML RGBA to
	 * @param int $color
	 * @return array
	 */
	public static function colorToRGBAArray($color){
		$c = [
			'red' => ($color >> 24) & 255,
			'green' => ($color >> 16) & 255,
			'blue' => ($color >> 8) & 255,
			'alpha' => $color & 255
		];

		$c['alpha'] = round( (255-$c['alpha']) * 127 / 255);
		return $c;
	}
	
}
