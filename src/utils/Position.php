<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

/**
 * Image crop position.
 */
class Position
{

	private array $position = [
		'x' => 0,
		'y' => 0
	];

	private array $dimensions = [];
	private string $cropPosition = 'center';

	public function get(): string
	{
		return $this->cropPosition;
	}

	/**
	 * Has options center, left, right, 
	 * top, topLeft, topRight, 
	 * bottom, bottomLeft or bottomRight.
	 * @param string $cropPosition
	 */
	public function set(string $cropPosition)
	{
		$this->cropPosition = $cropPosition;
	}

	private function center()
	{
		($this->dimensions['x'] >= $this->dimensions['y']) ?
			$this->position['x'] = ($this->dimensions['x'] - $this->dimensions['y']) / 2 :
			$this->position['y'] = ($this->dimensions['y'] - $this->dimensions['x']) / 2;
	}

	private function left()
	{
		$this->position['x'] = 0;
	}

	private function right()
	{
		$this->position['x'] = $this->dimensions['x'] - $this->dimensions['y'];
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

	private function topRight()
	{
		$this->position['y'] = 0;
		$this->position['x'] = $this->dimensions['x'] - $this->dimensions['y'];
	}

	private function bottom()
	{
		$this->position['y'] = $this->dimensions['y'] - $this->dimensions['x'];
	}

	private function bottomLeft()
	{
		$this->position['y'] = $this->dimensions['y'] - $this->dimensions['x'];
		$this->position['x'] = 0;
	}

	private function bottomRight()
	{
		$this->position['y'] = $this->dimensions['y'] - $this->dimensions['x'];
		$this->position['x'] = $this->dimensions['x'] - $this->dimensions['y'];
	}

	public function new(array $dimensions): array
	{
		$method = $this->get();

		if (method_exists(__CLASS__, $method)) {
			$this->dimensions = $dimensions;
			$this->$method();
		}

		return $this->position;
	}
}