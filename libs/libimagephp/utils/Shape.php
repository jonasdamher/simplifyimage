<?php

namespace libimagephp\LibImageUtils;

/**
 * Image shape crop.
 * 
 * default,
 * circle, square, 
 * v_rectangle, h_rectangle.
*/
class Shape {

	private string $type = 'default';

	public function get() : string {
		return $this->type;
	}

	public function set(string $type) {
		$this->type = $type;
	}
	
	public function modify($position, $dimensions) {

		switch ($this->get() ) {
			case 'circle':

				$position = $this->cropPosition($dimensions);

				$min = min($dimensions['x'], $dimensions['y']);
				$dimensions['x'] = $min;
				$dimensions['y'] = $min;

				$croppedImage = imagecrop($image, [
					'x' => $position['x'],
					'y' => $position['y'],
					'width' => $dimensions['x'],
					'height' => $dimensions['y']
				]);

				// Mask circle
				// Create mask circle
				$mask = \imagecreatetruecolor($min, $min);
				\imagealphablending($mask, false);
				
				// Colors
				$magentaColor = \imagecolorallocatealpha($mask, 255, 0, 255, 0);
				$transparent = \imagecolorallocatealpha($mask, 255, 255, 255, 127);

				// Add color mask
				imagefill($mask, 0, 0, $magentaColor);
				// Draw circle border line mask
				\imagearc($mask,
				$min/2, $min/2,
				$min, $min,
				0, 360, 
				$transparent);
				// Fill circle
				\imagefilltoborder($mask,
				$min/2, $min/2,
				$transparent, $transparent);
				// Mask circle final
				
				// Image
				\imagealphablending($croppedImage, true);
				// Add mask to image
				\imagecopyresampled($croppedImage, $mask, 
				0, 0, 0, 0,
				$min, $min,
				$min, $min);
				// remove mask color to image
				\imagecolortransparent($croppedImage, $magentaColor);
				
				\imagedestroy($mask);

				return $croppedImage;
			break;
			case 'square':

				$min = min($dimensions['x'], $dimensions['y']);
				$dimensions['x'] = $min;
				$dimensions['y'] = $min;
			break;
			case 'h_rectangle':

				$heightRedimension = ceil(($dimensions['x'] / 161) * 100);
				$dimensions['y'] += ($dimensions['x'] - $heightRedimension) / 2;

				$position['x'] += $dimensions['y'];
				$dimensions['y'] = $heightRedimension;
			break;
			case 'v_rectangle':

				$widthRedimension = ceil(($dimensions['y'] / 161) * 100);
				$dimensions['x'] += ($dimensions['y'] - $widthRedimension) / 2;

				$position['y'] += $dimensions['x'];
				
				$dimensions['x'] = $widthRedimension;
			break;
		}
		
		return $dimensions;
	}
}

?>