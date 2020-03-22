<?php 

namespace libimagephp\LibImageUtils;

/**
 * Add new scale to image.
 */
class Scale {

	private array $dimensions = [
		'width' => -1,
		'height' => -1
	];

	public function get() : array {
		return $this->dimensions;
	}

	/**
	 * Width and height image
	 *
	 * @param int $width
	 * @param int $height (optional) by default equal width
	*/
	public function set(int $width, int $height = -1) {
		$this->dimensions['width'] = $width;
		$this->dimensions['height'] = $height;
	}

	public function modify($image) {

		if($this->get()['width'] != -1 || $this->get()['height'] != -1) {

			$image = imagescale(
				$image,
				$this->get()['width'],
				$this->get()['height'],
				IMG_BILINEAR_FIXED
			);
		}

    return $image;
  }

}

?>