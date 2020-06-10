<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage\Utils;

use Jonasdamher\Simplifyimage\Core\ResponseHandler;
use Jonasdamher\Simplifyimage\Utils\Position;
use Jonasdamher\Simplifyimage\Utils\Shape;

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
		try {
			$crop = imagecrop($image, [
				'x' => $position['x'],
				'y' => $position['y'],
				'width' => $shape['x'],
				'height' => $shape['y']
			]);

			if (!$crop) {
				throw new \Exception('image cropping error');
			}
		} catch (\Exception $e) {
			$crop = $image;
			ResponseHandler::fail($e->getMessage());
		} finally {
			return $crop;
		}
	}

	private function verifyIfThePropertyChange(): bool
	{
		return $this->shape->get() != 'default';
	}

	public function modify($image)
	{
		try {
			if ($this->verifyIfThePropertyChange()) {
				$finalImage = $image;
				$dimensions = $this->dimensions($finalImage);

				$position = $this->position->new($dimensions);

				$imageWithShape = $this->shape->modify($finalImage, $position, $dimensions);

				if (is_array($imageWithShape)) {
					$image = $this->cropped($finalImage, $position, $imageWithShape);
				} else if (is_resource($imageWithShape)) {
					$image = $imageWithShape;
				} else {
					throw new \Exception('Could not shape image');
				}
			}
		} catch (\Exception $e) {
			ResponseHandler::fail($e->getMessage());
		} finally {
			return $image;
		}
	}
}
