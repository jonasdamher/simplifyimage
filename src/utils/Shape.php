<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage\Utils;

use Jonasdamher\Simplifyimage\Core\ResponseHandler;

/**
 * Image shape crop.
 * 
 * default,
 * circle, square, 
 * verticalRectangle, horizontalRentangle.
 */
class Shape
{

	private array $AllowsShapesTypes = ['horizontalRentangle', 'verticalRectangle', 'circle', 'square', 'default'];
	private string $type = 'default';

	public function get(): string
	{
		return $this->type;
	}

	/**
	 * Has options default, horizontalRentangle, verticalRectangle, 
	 * circle, square.
	 * @param string $type
	 */
	public function set(string $type)
	{
		try {
			if (!in_array($type, $this->AllowsShapesTypes, true)) {
				throw new \Exception("Don't exist shape (" . $type . ')');
			}
		} catch (\Exception $e) {
			$type = 'default';
			ResponseHandler::fail($e->getMessage());
		} finally {
			$this->type = $type;
		}
	}

	private function minBetweenDimensions(array $dimensions): int
	{
		return min($dimensions['x'], $dimensions['y']);
	}

	private function square(array $dimensions): array
	{
		$min = $this->minBetweenDimensions($dimensions);
		$dimensions['x'] = $min;
		$dimensions['y'] = $min;
		return $dimensions;
	}

	private function horizontalRentangle(array $dimensions, array $position): array
	{
		$heightRedimension = ceil(($dimensions['x'] / 161) * 100);
		$position['x'] += ($dimensions['x'] - $heightRedimension) / 2;
		$dimensions['y'] = $heightRedimension;

		return $dimensions;
	}

	private function verticalRectangle(array $dimensions, array $position): array
	{
		$widthRedimension = ceil(($dimensions['y'] / 161) * 100);
		$position['y'] += ($dimensions['y'] - $widthRedimension) / 2;
		$dimensions['x'] = $widthRedimension;

		return $dimensions;
	}

	private function circle($image, array $dimensions, array $position)
	{
		try {
			$newDimensions = $this->square($dimensions);
			$min = $this->minBetweenDimensions($dimensions);

			$croppedImage = imagecrop($image, [
				'x' => $position['x'],
				'y' => $position['y'],
				'width' => $newDimensions['x'],
				'height' => $newDimensions['y']
			]);

			// Create mask circle
			$circleMask = imagecreatetruecolor($min, $min);

			if (!is_resource($croppedImage) || !is_resource($circleMask)) {
				throw new \Exception('Could not apply circular shape.');
			}

			imagealphablending($circleMask, false);

			// Create colors
			$magentaColor = imagecolorallocatealpha($circleMask, 255, 0, 255, 0);
			$transparent = imagecolorallocatealpha($circleMask, 255, 255, 255, 127);

			// Add color mask
			imagefill($circleMask, 0, 0, $magentaColor);

			// Draw circle border line circleMask
			imagearc(
				$circleMask,
				$min / 2,
				$min / 2,
				$min,
				$min,
				0,
				360,
				$transparent
			);

			// Fill circle
			imagefilltoborder(
				$circleMask,
				$min / 2,
				$min / 2,
				$transparent,
				$transparent
			);
			// Mask circle final

			// Add alpha channel to image
			imagealphablending($croppedImage, true);

			// Add mask to image
			imagecopyresampled(
				$croppedImage,
				$circleMask,
				0,
				0,
				0,
				0,
				$min,
				$min,
				$min,
				$min
			);

			// remove mask color to image
			imagecolortransparent($croppedImage, $magentaColor);

			imagedestroy($image);
		} catch (\Exception $e) {
			$croppedImage = $image;
			ResponseHandler::fail($e->getMessage());
		} finally {
			return $croppedImage;
		}
	}

	public function modify($image, array $position, array $dimensions)
	{
		$shapeType = $this->get();
		$shape = false;

		switch ($shapeType) {
			case 'horizontalRentangle':
			case 'verticalRectangle':
				$shape = $this->$shapeType($dimensions, $position);
				break;
			case 'square':
				$shape = $this->$shapeType($dimensions);
				break;
			case 'circle':
				$shape = $this->$shapeType($image, $dimensions, $position);
				break;
		}
		return $shape;
	}
}
