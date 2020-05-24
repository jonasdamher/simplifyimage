<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

/**
 * Image crop position.
 * 
 * center, 
 * top, bottom, 
 * left, right,
 * topLeft, topRight, 
 * bottomLeft, bottomRight.
 */
class Position
{

	private string $cropPosition = 'center';

	public function get(): string
	{
		return $this->cropPosition;
	}

	public function set(string $cropPosition)
	{
		$this->cropPosition = $cropPosition;
	}

	public function new(array $dimensions): array
	{

		$position = [
			'x' => 0,
			'y' => 0
		];

		switch ($this->get()) {
			case 'center':
				($dimensions['x'] >= $dimensions['y']) ?
					$position['x'] = ($dimensions['x'] - $dimensions['y']) / 2 :
					$position['y'] = ($dimensions['y'] - $dimensions['x']) / 2;
				break;
			case 'top':

				$position['y'] = 0;
				break;
			case 'topLeft':

				$position['y'] = 0;
				$position['x'] = 0;
				break;
			case 'topRight':

				$position['y'] = 0;
				$position['x'] = $dimensions['x'] - $dimensions['y'];
				break;
			case 'bottom':

				$position['y'] = $dimensions['y'] - $dimensions['x'];
				break;
			case 'bottomLeft':

				$position['y'] = $dimensions['y'] - $dimensions['x'];
				$position['x'] = 0;
				break;
			case 'bottomRight':

				$position['y'] = $dimensions['y'] - $dimensions['x'];
				$position['x'] = $dimensions['x'] - $dimensions['y'];
				break;
			case 'left':

				$position['x'] = 0;
				break;
			case 'right':

				$position['x'] = $dimensions['x'] - $dimensions['y'];
				break;
		}

		return $position;
	}
}