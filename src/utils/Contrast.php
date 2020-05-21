<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

class Contrast
{

	private int $contrast = 0;

	public function get(): int
	{
		return $this->contrast;
	}

	/**
	 * Image constrast.
	 * Options: low, medium, hight, default.
	 * 
	 * @default By "default".
	 * 
	 */
	public function set(string $contrast)
	{

		switch ($contrast) {
			case 'low':
				$contrastNumber = -10;
				break;
			case 'medium':
				$contrastNumber = -50;
				break;
			case 'hight':
				$contrastNumber = -80;
				break;
			case 'default':
			default:
				$contrastNumber = 0;
				break;
		}
		$this->contrast = $contrastNumber;
	}

	// Contrast 
	public function modify($image)
	{
		if ($this->get() != 0) {

			imagefilter($image, IMG_FILTER_CONTRAST, $this->get());
		}
		return $image;
	}
}
?>