<?php

namespace Cerdic\Geometrize;

use \Cerdic\Geometrize\Model;
use \Cerdic\Geometrize\Exporter\SvgExporter;
use \Cerdic\Geometrize\Shape\Rectangle;

class ImageRunner {

	/**
	 * @var \Cerdic\Geometrize\Model
	 */
	protected $model;

	/**
	 * Geometrization steps : score, color, shape
	 * @var array
	 */
	protected $geometrizationSteps = [];


	/**
	 * ImageRunner constructor.
	 * @param \Cerdic\Geometrize\Bitmap $inputImage
	 * @param int|bool $backgroundColor
	 *   32bits encoded color or true for automatic $backgroundColor or false for no background (transparent)
	 * @throws \Exception
	 */
	public function __construct($inputImage, $backgroundColor){
		$this->model = new Model($inputImage, $backgroundColor);
		$this->geometrizationSteps = [];
	}

	/**
	 * @param array $options
	 * @param int $nb_steps
	 * @return float
	 * @throws \Exception
	 */
	public function steps($options, $nb_steps = 1){
		for ($i=0;$i<$nb_steps;$i++) {
			$this->geometrizationSteps[] = $this->model->step($options['shapeTypes'], $options['alpha'], $options['candidateShapesPerStep'], $options['shapeMutationsPerStep']);
		}
		return $this->getScore();
	}

	/**
	 * @param \Cerdic\Geometrize\Bitmap $rescaledImage
	 * @throws \Exception
	 * @return float
	 */
	public function reScale($rescaledImage) {

		$previousSteps = $this->geometrizationSteps;
		$backgroundColor = $this->model->getBackgroundColor();
		$this->model = new Model($rescaledImage, $backgroundColor);
		$this->geometrizationSteps = [];

		if (count($previousSteps)){
			$w = $rescaledImage->width;
			$h = $rescaledImage->height;
			foreach ($previousSteps as $step) {
				$alpha = $step['shape']->color & 255;
				// rescale the shape on new bounds
				$step['shape']->rescale($w, $h);
				$this->geometrizationSteps[] = $this->model->addShape($step['shape'], $alpha);
			}
		}

		return $this->getScore();
	}

	/**
	 * Export the result as a SVG
	 * @param int $imageWidth
	 * @param int $imageHeight
	 * @return string
	 */
	public function exportToSVG($imageWidth=0, $imageHeight=0) {

		$shapes = array_column($this->geometrizationSteps, 'shape');

		// add background shape if necessary
		$backgroundColor = $this->model->getBackgroundColor();
		// 0 or false are transparents, nothing needed
		if ($backgroundColor) {
			$backgroundShape = new Rectangle($this->model->getWidth(), $this->model->getHeight(), 1, true);
			$backgroundShape->color = $backgroundColor;
			array_unshift($shapes, $backgroundShape);
		}

		$svg_image = SvgExporter::export($shapes, $this->model->getWidth(), $this->model->getHeight(), $imageWidth, $imageHeight);
		return $svg_image;

	}

	/**
	 * Get the current Score (ie score of the last step)
	 * @return float
	 */
	public function getScore() {

		if (!count($this->geometrizationSteps)) {
			return 1.0;
		}
		$last = end($this->geometrizationSteps);
		return $last['score'];
	}


	/**
	 * Get the current number of steps
	 * @return int
	 */
	public function getNbSteps() {
		return count($this->geometrizationSteps);
	}


	/**
	 * Get the results
	 * @return array
	 */
	public function getResults() {
		return $this->geometrizationSteps;
	}

	/**
	 * @return \Cerdic\Geometrize\Bitmap
	 * @throws \Exception
	 */
	public function getImageData(){
		if (!($this->model!==null)){
			throw new \Exception("FAIL: model != null");
		}
		return $this->model->getCurrent();
	}

	/**
	 * @return \Cerdic\Geometrize\Model
	 */
	public function getModel() {
		return $this->model;
	}

}
