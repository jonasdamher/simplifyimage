<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

/**
 * Image shape crop.
 * 
 * default,
 * circle, square, 
 * v_rectangle, h_rectangle.
 */
class Shape
{

	private string $type = 'default';

	public function get(): string
	{
		return $this->type;
	}

	public function set(string $type)
	{
		$this->type = $type;
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

		$newDimensions = $this->square($dimensions);
		$min = $this->minBetweenDimensions($dimensions);

		$croppedImage = imagecrop($image, [
			'x' => $position['x'],
			'y' => $position['y'],
			'width' => $newDimensions['x'],
			'height' => $newDimensions['y']
		]);

		// Create mask circle
		$mask = imagecreatetruecolor($min, $min);
		imagealphablending($mask, false);

		// crete colors
		$magentaColor = imagecolorallocatealpha($mask, 255, 0, 255, 0);
		$transparent = imagecolorallocatealpha($mask, 255, 255, 255, 127);

		// Add color mask
		imagefill($mask, 0, 0, $magentaColor);
		// Draw circle border line mask
		imagearc(
			$mask,
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
			$mask,
			$min / 2,
			$min / 2,
			$transparent,
			$transparent
		);
		// Mask circle final

		// Image
		imagealphablending($croppedImage, true);
		// Add mask to image
		imagecopyresampled(
			$croppedImage,
			$mask,
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

		imagedestroy($mask);
		return $croppedImage;
	}

	public function modify($image, array $position, array $dimensions)
	{

		$shape = null;

		switch ($this->get()) {
			case 'h_rectangle':
				$shape = $this->horizontalRentangle($dimensions, $position);
				break;
			case 'v_rectangle':
				$shape = $this->verticalRectangle($dimensions, $position);
				break;
			case 'circle':
				$shape = $this->circle($image, $dimensions, $position);
				break;
			case 'square':
			default:
				$shape = $this->square($dimensions);
				break;
		}

		return $shape;
	}
}
