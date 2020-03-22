<?php 

namespace libimagephp\LibImageUtils;

require_once 'Position.php';
require_once 'Shape.php';

use libimagephp\LibImageUtils\Position;
use libimagephp\LibImageUtils\Shape;

/**
 * Image crop, position and shape crop.
 */
class Crop {

	public Position $position;
	public Shape $shape;

	public function __construct() {

		$this->position = new Position();
		$this->shape = new Shape();
	}

	public function modify($image) {

		$dimensions = [
			'x' => imagesx($image),
			'y' => imagesy($image)
		];

		$position =  [
			'x' => 0,
			'0'
		];

		$croppedImage = imagecrop($image, [
			'x' => $position['x'],
			'y' => $position['y'],
			'width' => $dimensions['x'],
			'height' => $dimensions['y']
		]);
		
		return $croppedImage;
  }

}

?>