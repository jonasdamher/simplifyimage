<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

/**
 * For set path where the images will be saved.
 */
class Path
{

	private string $path = '';

	public function get(): string
	{
		return $this->path;
	}

	/**
	 * Specify the path where the images will be saved.
	 * @param string $path
	 * @example public/images/
	 */
	public function set(string $path)
	{
		$this->path = $path;
	}

	/**
	 * Verify if path exist.
	 * @return bool
	 */
	public function exist(): bool
	{
		return (is_dir($this->get()));
	}
}