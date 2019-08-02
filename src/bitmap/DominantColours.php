<?php

namespace Cerdic\Geometrize\Bitmap;

use \Cerdic\Geometrize\Bitmap;

/**
 * Dominant colours by k means derived from code by Charles Leifer at:
 *   http://charlesleifer.com/blog/using-python-and-k-means-to-find-the-dominant-colors-in-images/
 *
 * Greatly Forked from
 *   https://gist.github.com/pgchamberlin/7092958
 *
 */
class DominantColours {

	/**
	 * @param int $nbColors
	 *   number of Colors we want to extract
	 * @param Bitmap $target
	 *   target Bitmap
	 * @param array $lines
	 *   scanlines of the shape in the target Bitmap
	 * @return array
	 */
	static public function dominantColours($nbColors, $target, $lines) {
		$points = [];
		foreach($lines as $line){
			$y = $line['y'];
			for ($x=$line['x1']; $x<=$line['x2']; $x++) {
				$points[] = $t = $target->data[$y][$x];
			}
		}

		$clusters = DominantColours::kMeans($points, $nbColors);

		$colours = [];
		foreach ($clusters as $cluster) {
			$center = $cluster[0];
			$nbPoints = count($cluster[1]);
			$colours[$center] = $nbPoints;
		}

		arsort($colours);
		return array_keys($colours);
	}

	/**
	 * kMeans algorithm
	 * @param array $points
	 * @param int $nbClusters
	 * @return array
	 */
	static protected function kMeans($points, $nbClusters){
		$clusters = array();

		// start with enough clusters to avoid too near points
		$shuffle = array_keys($points);
		shuffle($shuffle);
		$seen = [];
		$refused = 0;
		while (count($clusters)<$nbClusters+10 && count($shuffle)){
			$index = array_shift($shuffle);
			if (!isset($seen[$points[$index]])
			  && (!count($clusters) || DominantColours::minDistanceBetween($points[$index], array_column($clusters, 0))>5 || $refused++>$nbClusters)
			) {
				$seen[$points[$index]] = true;
				$clusters[] = [$points[$index], []];
			}
		}

		// initial cluster distribution
		$distribute = DominantColours::distributePoints($points, $clusters);
		foreach ($distribute as $indexPoint => $indexCluster) {
			$clusters[$indexCluster][1][] = $points[$indexPoint];
		}

		$maxIter = 50; // avoid infinite loop
		while ($maxIter-->0){

			// compute center of each clusters and create new clusters
			$newClusters = [];
			$nbSmallest = null;
			$indexSmallest = null;
			$remove = true;
			foreach ($clusters as $index => $cluster) {
				$center = DominantColours::centerOfPoints($cluster[1]);
				if (!count($newClusters) || DominantColours::minDistanceBetween($center, array_column($newClusters, 0))>20) {
					$newClusters[] = [$center, []];
					if (is_null($indexSmallest) or count($cluster[1])<$nbSmallest) {
						$indexSmallest = $index;
						$nbSmallest = count($cluster[1]);
					}
				}
				else {
					$remove = false;
				}
			}
			// remove the smallest cluster if still too many
			if ($remove && count($newClusters)>$nbClusters) {
				unset($newClusters[$indexSmallest]);
			}

			// redistribute along new clusters and watch for any changement
			$changed = false;
			foreach ($clusters as $indexCluster => $cluster) {
				$distribute = DominantColours::distributePoints($cluster[1], $newClusters);
				if (count(array_diff($distribute, [$indexCluster]))) {
					$changed = true;
					$countChanged = 0;
					foreach ($distribute as $indexPoint => $indexNewCluster) {
						$newClusters[$indexNewCluster][1][] = $cluster[1][$indexPoint];
						if ($indexNewCluster !== $indexCluster) {
							$countChanged++;
						}
					}
				}
				else {
					// unchanged points
					$newClusters[$indexCluster][1] = $cluster[1];
				}
			}

			$clusters = $newClusters;

			if (!$changed) {
				break;
			}
		}

		return $clusters;
	}

	/**
	 * Distribute points into given clusters
	 * @param array $points
	 * @param array $clusters
	 * @return array
	 */
	static protected function distributePoints($points, $clusters) {
		$distribute = [];
		foreach ($points as $indexPoint => $p) {
			$bestDistance = null;
			foreach ($clusters as $indexCluster => $cluster) {
				$e = 0;
				foreach ([24,16,8,0] as $k){
					$dk = ($p>>$k & 255)-($cluster[0]>>$k & 255);
					if ($dk<0){
						$dk *= -1;
					}
					$e += $dk;
				}
				if (is_null($bestDistance) or $e < $bestDistance) {
					$distribute[$indexPoint] = $indexCluster;
					$bestDistance = $e;
				}
			}
		}
		return $distribute;
	}

	/**
	 * Determine the mean center of an array of points
	 * @param $points
	 * @return int
	 */
	static protected function centerOfPoints($points) {
		$center = [24 => 0, 16 => 0, 8 => 0, 0=>0];
		foreach ($points as $p){
			foreach ([24,16,8,0] as $k){
				$center[$k] += ($p>>$k & 255);
			}
		}
		$nbPoints = count($points);
		$c = 0;
		foreach ($center as $k => $v) {
			$v = round($v / $nbPoints);
			$c += ($v&255) << $k;
		}
		return $c;
	}

	/**
	 * Find the smallest distance between a point and an array of points
	 * @param int $p1
	 * @param array $points
	 * @return float
	 */
	static protected function minDistanceBetween($p1, $points) {
		$min = null;
		foreach ($points as $p) {
			$e = 0;
			foreach ([24,16,8,0] as $k){
				$dk = ($p1>>$k & 255)-($p>>$k & 255);
				if ($dk<0){
					$dk *= -1;
				}
				$e += $dk;
			}
			if (is_null($min) or $e < $min) {
				$min = $e;
			}
		}
		return $e / 4;
	}

	/**
	 * Compute distance between 2 points
	 * @param int $p1
	 * @param int $p2
	 * @return float|int
	 */
	static protected function distanceBetween($p1, $p2) {
		$e = 0;
		foreach ([24,16,8,0] as $k){
			$dk = ($p1>>$k & 255)-($p2>>$k & 255);
			if ($dk<0){
				$dk *= -1;
			}
			$e += $dk;
		}
		return $e / 4;
	}
}