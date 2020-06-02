<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage\Utils;

use Jonasdamher\Simplifyimage\Core\ResponseHandler;

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
		try {
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
				default:
					$contrastNumber = 0;
					throw new \Exception("Don't exist option ($contrast) for contrast.");
					break;
			}
		} catch (\Exception $e) {
			ResponseHandler::fail($e->getMessage());
		} finally {
			$this->contrast = $contrastNumber;
		}
	}

	public function modify($image)
	{
		try {
			if ($this->get() != 0) {

				$imageWithContrast = $image;

				if (!imagefilter($imageWithContrast, IMG_FILTER_CONTRAST, $this->get())) {
					throw new \Exception('Fail to apply contrast in image.');
				}

				$image = $imageWithContrast;
			}
		} catch (\Exception $e) {
			ResponseHandler::fail($e->getMessage());
		} finally {
			return $image;
		}
	}
}
