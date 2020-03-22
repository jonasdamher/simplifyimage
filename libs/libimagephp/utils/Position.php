<?php 

namespace libimagephp\LibImageUtils;

/**
 * Posicion en la que se recorta la imagen, center, top, topLeft, topRight, bottom, bottomRight, right, left
 */
class Position {
	
	private string $cropPosition = 'center';

	public function get() : string {
		return $this->cropPosition;
	}

	/**
	 * Position for crop.
	 * Position center, left, right, top, bottom.
	 * @param string $cropPosition - Position type.
	 * @default center
	 */
	public function set(string $cropPosition) {
		$this->cropPosition = $cropPosition;
	}

	public function newPosition(array $pixelsImage) : array {
		
		$position = [
			'x' => 0,
			'y' => 0
		];

		switch($this->get() ) {
			case 'center':
				($pixelsImage['x'] >= $pixelsImage['y']) ? 
				$position['x'] = ($pixelsImage['x']-$pixelsImage['y'])/2 :
				$position['y'] = ($pixelsImage['y']-$pixelsImage['x'])/2;
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
				$position['x'] = $pixelsImage['x']-$pixelsImage['y'];
			break;
			case 'bottom':
			
				$position['y'] = $pixelsImage['y']-$pixelsImage['x'];
			break;
			case 'bottomLeft':
			
				$position['y'] = $pixelsImage['y']-$pixelsImage['x'];
				$position['x'] = 0;
			break;
			case 'bottomRight':
			
				$position['y'] = $pixelsImage['y']-$pixelsImage['x'];
				$position['x'] = $pixelsImage['x']-$pixelsImage['y'];
			break;
			case 'left':
			
				$position['x'] = 0;
			break;
			case 'right':

				$position['x'] = $pixelsImage['x']-$pixelsImage['y'];
			break;
		}
	
		return $position;
	}

}

?>