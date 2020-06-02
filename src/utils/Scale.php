<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage\Utils;

use Jonasdamher\Simplifyimage\Core\ResponseHandler;

/**
 * Add new scale to image.
 */
class Scale
{

	private array $dimensions = [
		'width' => -1,
		'height' => -1
	];

	public function get(): array
	{
		return $this->dimensions;
	}

	/**
	 * New width and height image.
	 * @param int $width
	 * @param int $height (optional) by default equal width
	 */
	public function set(int $width, int $height = -1)
	{
		$this->dimensions['width'] = $width;
		$this->dimensions['height'] = $height;
	}

	public function modify($image)
	{
		try {
			if ($this->get()['width'] != -1 || $this->get()['height'] != -1) {

				$imageWithNewScale = imagescale(
					$image,
					$this->get()['width'],
					$this->get()['height'],
					IMG_BILINEAR_FIXED
				);
				if (!$imageWithNewScale) {
					throw new \Exception('Error with scale, dimesions(' . $this->get()['width'] . ',' . $this->get()['height'] . ').');
				}

				$image = $imageWithNewScale;
			}
		} catch (\Exception $e) {
			ResponseHandler::fail($e->getMessage());
		} finally {
			return $image;
		}
	}
}
