<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

use Jonasdamher\Libimagephp\Utils\Position;
use Jonasdamher\Libimagephp\Utils\Shape;

/**
 * Image crop, position and shape crop.
 */
class Crop
{

	public Position $position;
	public Shape $shape;

	public function __construct()
	{
		$this->position = new Position;
		$this->shape = new Shape;
	}

	private function dimensions($image): array
	{
		return [
			'x' => imagesx($image),
			'y' => imagesy($image)
		];
	}

	private function cropped($image, array $position, array $shape)
	{
		return imagecrop($image, [
			'x' => $position['x'],
			'y' => $position['y'],
			'width' => $shape['x'],
			'height' => $shape['y']
		]);
	}

	public function modify($image)
	{

		if ($this->shape->get() == 'default') {
			return $image;
		}

		$dimensions = $this->dimensions($image);

		$position = $this->position->new($dimensions);

		$imageWithShape = $this->shape->modify($image, $position, $dimensions);

		if ($this->shape->get() == 'circle') {
			return $imageWithShape;
		}

		return $this->cropped($image, $position, $imageWithShape);
	}
}
