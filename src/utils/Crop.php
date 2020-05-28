<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

use Jonasdamher\Libimagephp\Core\ResponseHandler;
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

	public function exist(): bool
	{
		return $this->shape->get() == 'default';
	}

	public function modify($image)
	{
		try {

			$dimensions = $this->dimensions($image);

			$position = $this->position->new($dimensions);

			$imageWithShape = $this->shape->modify($image, $position, $dimensions);

			if (!$imageWithShape) {
				throw new \Exception('Could not crop image');
			}

			if ($this->shape->get() == 'circle') {
				$finalImage = $imageWithShape;
			} else {
				$finalImage = $this->cropped($image, $position, $imageWithShape);
			}
		} catch (\Exception $e) {
			$finalImage = $image;
			ResponseHandler::fail($e->getMessage());
		} finally {
			return $finalImage;
		}
	}
}
