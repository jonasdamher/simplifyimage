<?php

declare(strict_types=1);

namespace libimagephp\LibImageUtils;

// require_once 'Position.php';
// require_once 'Shape.php';

use libimagephp\LibImageUtils\Position;
use libimagephp\LibImageUtils\Shape;

/**
 * Image crop, position and shape crop.
 */
class Crop
{

	public Position $position;
	public Shape $shape;

	public function __construct()
	{

		$this->position = new Position();
		$this->shape = new Shape();
	}

	public function modify($image)
	{

		if ($this->shape->get() == 'default') {
			return $image;
		}

		$dimensions = [
			'x' => imagesx($image),
			'y' => imagesy($image)
		];

		$position = $this->position->new($dimensions);

		$shape = $this->shape->modify($position, $dimensions);

		$croppedImage = imagecrop($image, [
			'x' => $position['x'],
			'y' => $position['y'],
			'width' => $shape['x'],
			'height' => $shape['y']
		]);

		return $croppedImage;
	}
}
?>