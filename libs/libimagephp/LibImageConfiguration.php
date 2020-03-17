<?php 

namespace libimagephp\config;

class LibImageConfiguration {

	protected array $response = [
		'valid' => true, 
		'filename' => null, 
		'errors' => ''
	];

	private string $headNameFile =  '';

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

	/**
   * Imagen antigua, testeando por ahora, variable prueba
	 * @example imagenAntigua.png
	*/
	private $oldImageName;
	
	// GETS & SETS
	
	/**
	 * Devuelve el nombre del input del formulario
	*/
	protected function getNameInputFile() : string {
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
	protected function getPath() : string {
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

	protected function getHeadNameFile() : string {
		return $this->headNameFile;
	}

	/**
	 * Head name file.
	 * 
	 * Not allows special simbols.
	 */
	public function setHeadNameFile(string $headNameFile) {
		$this->headNameFile = $headNameFile;
	}

	/**
	 * Devuelve el tamaño máx permitido para subida de imagenes
	*/
	protected function getMaxSize() : int {
		return $this->maxSize;
	}

	/**
	 * Max size allow for upload images
	 *
	 * By default is 2097152 bytes (2 MB)
	 * @param int $maxSize 
	*/
	public function setMaxSize(int $maxSize) {
		$this->maxSize = $maxSize;
	}

	protected function getScale() : array {
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
	
	protected function getCropType() : string {
		return $this->cropType;
	}

	public function setCropType(string $cropType) {
		$this->cropType = $cropType;
	}

	public function requiredImage(){
		$this->requiredImage = true;
	}

	protected function getrequiredImage() : bool {
		return $this->requiredImage;
	}

	protected function getCropPosition() : string {
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

	protected function getAllowedFormats() : array {
		return $this->allowedFormats;
	}
	
	public function setConversionTo(string $conversionTo) {
		$this->conversionTo = $conversionTo;
	}

	protected function getConversionTo() : string {
		return $this->conversionTo;
	}

	public function setOldImageName(string $oldImageName) {
		$this->oldImageName = $oldImageName;
	}

	protected function getOldImageName() : string {
		return $this->oldImageName;
	}

	// MODIFY IMAGE

	private function cropPosition(array $pixelsImage) {
		
		$position = [
			'x' => 0,
			'y' => 0
		];

		switch($this->getCropPosition() ) {
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

	protected function crop($image) {

		$pixelsImage = [
			'x' => imagesx($image),
			'y' => imagesy($image)
		];
		
		switch ($this->getCropType() ) {
			case 'circle':

				$position = $this->cropPosition($pixelsImage);

				$dimensionMin = min($pixelsImage['x'], $pixelsImage['y']);
				
				$croppedImage = imagecrop($image, [
					'x' => $position['x'], 
					'y' => $position['y'], 
					'width' => $dimensionMin, 
					'height' => $dimensionMin
				]);

				// Mask circle
				$mask = \imagecreatetruecolor($dimensionMin, $dimensionMin);

				// Color
				$magentaColor = \imagecolorallocate($mask, 255, 0, 255);
				$transparent = \imagecolorallocatealpha($mask, 255, 255, 255, 127);

				// Add color
				imagefill($mask, 0, 0, $magentaColor);

				\imagealphablending($mask, false);

				// Draw circle border line
				\imagearc($mask,
				$dimensionMin/2, $dimensionMin/2,
				$dimensionMin, $dimensionMin,
				0, 360, 
				$transparent);
				
				// Fill circle
				\imagefilltoborder($mask,
				$dimensionMin/2, $dimensionMin/2,
				$transparent, $transparent);
				
				// Mask circle final

				// Image
				\imagealphablending($croppedImage, true);
				\imagecopyresampled($croppedImage, $mask, 
				0, 0, 0, 0,
				$dimensionMin, $dimensionMin,
				$dimensionMin, $dimensionMin);

				\imagecolortransparent($croppedImage, $magentaColor);

				return $croppedImage;

			break;
			case 'square':

				$position = $this->cropPosition($pixelsImage);

				$dimensionMin = min($pixelsImage['x'], $pixelsImage['y']);
				
				$croppedImage = imagecrop($image, [
					'x' => $position['x'], 
					'y' => $position['y'], 
					'width' => $dimensionMin, 
					'height' => $dimensionMin
				]);

				return $croppedImage;
		
			break;
			case 'h_rectangle':

				$heightRedimension = ceil(($pixelsImage['x'] / 161) * 100);
				
				$pixelsImage['y'] += ($pixelsImage['x'] - $heightRedimension) / 2;

				$position = $this->cropPosition($pixelsImage);

				$croppedImage = imagecrop($image, [
					'x' => $position['x'],
					'y' => $position['y'],
					'width' => $pixelsImage['x'],
					'height' => $heightRedimension
				]);

				return $croppedImage;
		
			break;
			case 'v_rectangle':
				
				$widthRedimension = ceil(($pixelsImage['y'] / 161) * 100);

				$pixelsImage['x'] += ($pixelsImage['y'] - $widthRedimension) / 2;

				$position = $this->cropPosition($pixelsImage);

				$croppedImage = imagecrop($image, [
					'x' => $position['x'],
					'y' => $position['y'],
					'width' => $widthRedimension,
					'height' => $pixelsImage['y']
				]);

				return $croppedImage;
		
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

}

?>