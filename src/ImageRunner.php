<?php

namespace Cerdic\Geometrize;

use \Cerdic\Geometrize\Model;

class ImageRunner {

	/**
	 * @var \Cerdic\Geometrize\Model
	 */
	protected $model;

	/**
	 * The initial BackgroundColor used to init the Model
	 * @var
	 */
	protected $backgroundColor;

	/**
	 * Geometrization steps : score, color, shape
	 * @var array
	 */
	protected $geometrizationSteps = [];


	/**
	 * ImageRunner constructor.
	 * @param \Cerdic\Geometrize\Bitmap $inputImage
	 * @param int $backgroundColor
	 *   32bits encoded color
	 * @throws \Exception
	 */
	public function __construct($inputImage, $backgroundColor){
		$this->backgroundColor = $backgroundColor;
		$this->model = new Model($inputImage, $this->backgroundColor);
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
		$this->model = new Model($rescaledImage, $this->backgroundColor);
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
