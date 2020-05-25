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

	private array $position = [
		'x' => 0,
		'y' => 0
	];

	private string $cropPosition = 'center';

	public function get(): string
	{
		return $this->cropPosition;
	}

	public function set(string $cropPosition)
	{
		$this->cropPosition = $cropPosition;
	}

	private function center(array $dimensions)
	{
		($dimensions['x'] >= $dimensions['y']) ?
			$this->position['x'] = ($dimensions['x'] - $dimensions['y']) / 2 :
			$this->position['y'] = ($dimensions['y'] - $dimensions['x']) / 2;
	}

	private function top()
	{
		$this->position['y'] = 0;
	}

	private function topLeft()
	{
		$this->position['y'] = 0;
		$this->position['x'] = 0;
	}

	private function topRight(array $dimensions)
	{
		$this->position['y'] = 0;
		$this->position['x'] = $dimensions['x'] - $dimensions['y'];
	}

	private function bottom(array $dimensions)
	{
		$this->position['y'] = $dimensions['y'] - $dimensions['x'];
	}

	private function bottomLeft(array $dimensions)
	{
		$this->position['y'] = $dimensions['y'] - $dimensions['x'];
		$this->position['x'] = 0;
	}

	private function bottomRight($dimensions)
	{
		$this->position['y'] = $dimensions['y'] - $dimensions['x'];
		$this->position['x'] = $dimensions['x'] - $dimensions['y'];
	}

	private function left()
	{
		$this->position['x'] = 0;
	}

	private function right($dimensions)
	{
		$this->position['x'] = $dimensions['x'] - $dimensions['y'];
	}

	public function new(array $dimensions): array
	{

		switch ($this->get()) {
			case 'center':
				$this->center($dimensions);
				break;
			case 'top':
				$this->top();
				break;
			case 'topLeft':
				$this->topLeft();
				break;
			case 'topRight':
				$this->topRight($dimensions);
				break;
			case 'bottom':
				$this->bottom($dimensions);
				break;
			case 'bottomLeft':
				$this->bottomLeft($dimensions);
				break;
			case 'bottomRight':
				$this->bottomRight($dimensions);
				break;
			case 'left':
				$this->left();
				break;
			case 'right':
				$this->right($dimensions);
				break;
		}

		return $this->position;
	}
}