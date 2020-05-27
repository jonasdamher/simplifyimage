<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

/**
 * Handle image contrast.
 */
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
			case 'high':
				$contrastNumber = -80;
				break;
			case 'default':
			default:
				$contrastNumber = 0;
				break;
		}
		$this->contrast = $contrastNumber;
	}

	public function modify($image)
	{
		if ($this->get() != 0) {

			imagefilter($image, IMG_FILTER_CONTRAST, $this->get());
		}
		return $image;
	}
}