<?php 

namespace libimagephp\config;

class LibImageConfiguration {

	protected array $response = [
		'valid' => true, 
		'filename' => null, 
		'errors' => ''
	];

	private string $nameInputFile;

	private string $path;
	
	private array $allowedFormats = [
		'png', 
		'jpg', 
		'jpeg', 
		'gif',
		'webp'
	];

	private int $maxSize = 2097152 ; // 2 MB

	private array $scale = [
		'x' => -1, 
		'y' => -1
	];

	private $requiredImage = false;

	//Forma de recortar la imagen, square, v_rectangle, h_rectangle, default
	private string $cropType = 'default';

	//Posicion en la que se recorta la imagen, center, top, topLeft, topRight, bottom, bottomRight, right, left
	private string $cropPosition = 'center';

	// Convertir la imagen a otro formato
	// default, webp, png, jpeg, gif
	private string $conversionTo = 'default';

	// texto que se concatena con el tipo de imagen para conversión a webp * imagecreatefromjpeg	
	protected string $formatImage = 'imagecreatefrom'; 
	protected string $transformImage = 'image'; 

	// GETS & SETS
	
	/**
	 * Devuelve el nombre del input del formulario
	 */
	protected function getNameInputFile() {
		return $this->nameInputFile;
	}

	/**
	* Nombre del input del formulario
	* @param string $nameInputFile
	*/

	public function setNameInputFile(string $nameInputFile) {
		$this->nameInputFile = $nameInputFile;
	}

	/**
	 * Devuelve la ruta donde se guardará la imagen
	 */
	protected function getPath() {
		return $this->path;
	}

	/**
	* Especificar ruta donde se guardan las imagenes
	* @param string $path de directorio
	*	@example public/images/
	*/
	public function setPath(string $path) {
		$this->path = $path;
	}

	/**
	 * Devuelve el tamaño máx permitido para subida de imagenes
	 */
	protected function getMaxSize() {
		return $this->maxSize;
	}

	/**
	* Tamaño máximo permitido para subida de imagenes
	*
	* Especificar tamaño en bytes
	*
	* Por defecto son 2097152 bytes (2 MB)
	* @param int $maxSize 
	*/

	public function setMaxSize(int $maxSize) {
		$this->maxSize = $maxSize;
	}

	protected function getScale() {
		return $this->scale;
	}

	/**
	* Especificar ancho "x" y alto "y"
	*
	* Por defecto son 128 pixeles alto y ancho
	* @param int $x
	* @param int $y (opcional) por defecto es igual a $x
	*/

	public function setScale(int $x, int $y = -1) {
		$this->scale['x'] = $x;
		$this->scale['y'] = $y;
	}
	
	protected function getCropType() {
		return $this->cropType;
	}

	public function setCropType(string $cropType) {
		$this->cropType = $cropType;

	}

	protected function getCropPosition() {
		return $this->cropPosition;
	}

	/**
	 * Position for crop.
	 * Position center, left, right, top, bottom.
	 * @default center
	 * @param string $cropPosition - Position type.
	 */
	public function setCropPosition(string $cropPosition) {
		$this->cropPosition = $cropPosition;

	}

	protected function getAllowedFormats() {
		return $this->allowedFormats;
	}

	public function setAllowedFormats(string $allowedFormats) {
		$this->allowedFormats = $allowedFormats;
	}
	
	public function setConversionTo(string $conversionTo) {
		$this->conversionTo = $conversionTo;
	}

	protected function getConversionTo() {
		return $this->conversionTo;
	}

	// MODIFY IMAGE

	private function cropPosition(array $pixelsImage) {
		
		$position = [
			'x' => 0,
			'y' => 0
		];

		switch ($this->getCropPosition() ) {
			case 'center':
			
				($pixelsImage['x'] >= $pixelsImage['y']) ? 
				$position['x'] = ($pixelsImage['x']-$pixelsImage['y'])/2 :
				$position['y'] = ($pixelsImage['y']-$pixelsImage['x'])/2;
			break;
			case 'top':
			
				($pixelsImage['x'] >= $pixelsImage['y']) ? 
				$position['x'] = ($pixelsImage['x']-$pixelsImage['y'])/2 :
				$position['y'] = ($pixelsImage['y']-$pixelsImage['x'])/2;
			
				$position['y'] = 0;
			
			break;
			case 'bottom':
			
				($pixelsImage['x'] >= $pixelsImage['y']) ? 
				$position['x'] = ($pixelsImage['x']-$pixelsImage['y'])/2 :
				$position['y'] = ($pixelsImage['y']-$pixelsImage['x'])/2;
			
				$position['y'] = $pixelsImage['y']-$pixelsImage['x'];
			
			break;
			case 'left':
			
				($pixelsImage['x'] >= $pixelsImage['y']) ? 
				$position['x'] = ($pixelsImage['x']-$pixelsImage['y'])/2 :
				$position['y'] = ($pixelsImage['y']-$pixelsImage['x'])/2;
			
				$position['x'] = 0;
			
			break;
			case 'right':
			
				($pixelsImage['x'] >= $pixelsImage['y']) ? 
				$position['x'] = ($pixelsImage['x']-$pixelsImage['y'])/2 :
				$position['y'] = ($pixelsImage['y']-$pixelsImage['x'])/2;
			
				$position['x'] = $pixelsImage['x']-$pixelsImage['y'];
			
			break;
		}
	
			return $position;
	}

	protected function crop($image) {

		$pixelsImage = [
			'x' => imagesx($image),
			'y' => imagesy($image)
		];

		$position = $this->cropPosition($pixelsImage);

    // coge el numero mas bajo de los dos
		
		switch ($this->getCropType() ) {
			case 'square':

				$dimensionMin = min($pixelsImage['x'], $pixelsImage['y']);
				
				$cropped = imagecrop($image, [
					'x' => $position['x'], 
					'y' => $position['y'], 
					'width' => $dimensionMin, 
					'height' => $dimensionMin
				]);

				return $cropped;
		
			break;
			case 'h_rectangle':

				$hHeight =  ($pixelsImage['x'] * 60) / 100;

				$heightDiference = ($pixelsImage['x'] - $hHeight) / 2;

				$cropped = imagecrop($image, [
					'x' => $position['x'],
					'y' => $position['y'] + $heightDiference,
					'width' => $pixelsImage['x'],
					'height' => $hHeight
				]);

				return $cropped;
		
			break;
			case 'v_rectangle':
				
				$vWidth =  ($pixelsImage['y'] * 60) / 100;

				$widthDiference = ($pixelsImage['y'] - $vWidth) / 2;

				$cropped = imagecrop($image, [
					'x' => $position['x'] + $widthDiference,
					'y' => $position['y'],
					'width' => $vWidth,
					'height' => $pixelsImage['y']
				]);

				return $cropped;
		
			break;
		}

		return $image;
  }

  protected function scale($image) {

    $newImage = $this->getCropType() != 'default' ? $this->crop($image) : $image;

		if($this->getScale()['x'] != -1 ||  $this->getScale()['y'] != -1) {

			$newImage = imagescale(
				$newImage, 
				$this->getScale()['x'], 
				$this->getScale()['y'], 
				IMG_BILINEAR_FIXED
			);
		}

    return $newImage;
  }

	protected function conversionTo($image, string $target_file) {

		$new = ('image'.$this->getConversionTo() )($image, $target_file);
		
		if($new) {
			return $new;
		}
		
		return false;
	}

	// FINAL MODIFY IMAGE

	protected function upload($image, string $target_file) {

		return ($this->getConversionTo() != 'default') ? 
						$this->conversionTo($image, $target_file) : 
						($this->transformImage)($image, $target_file);
	}

	/**
	 * For error return if dont exist image.
	 */
	public function requiredImage(){
		$this->requiredImage = true;
	}

	protected function getrequiredImage(){
		return $this->requiredImage;
	}

}

?>