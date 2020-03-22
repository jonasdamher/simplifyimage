<?php 

namespace libimagephp\LibImageConfiguration;

class Configuration {

	protected array $response = [
		'valid' => true, 
		'filename' => null, 
		'errors' => []
	];

	private string $path = '';

	private string $nameInputFile = '';

	private string $prefixName =  '';

	private bool $requiredImage = false;

	private int $maxSize = 2097152 ; // 2 MB

	private int $contrast = 0;

	private array $allowedFormats = [
		'png', 
		'jpg', 
		'jpeg', 
		'gif',
		'webp'
	];

	private array $scale = [
		'x' => -1, 
		'y' => -1
	];

	//Forma de recortar la imagen, square, v_rectangle, h_rectangle, default
	private string $shapeType = 'default';

	//Posicion en la que se recorta la imagen, center, top, topLeft, topRight, bottom, bottomRight, right, left
	private string $cropPosition = 'center';

	// Image conversion to other format
	// @param string $conversionTo default, webp, png, jpeg, gif
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
	public function nameImputFile(string $nameInputFile) {
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
	public function path(string $path) {
		$this->path = $path;
	}

	protected function getPrefixName() : string {
		return $this->prefixName;
	}

	/**
	 * Head name file.
	 * 
	 * Not allows special simbols.
	 */
	public function prefixName(string $prefixName) {
		$this->prefixName = $prefixName;
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
	public function maxSize(int $maxSize) {
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
	public function scale(int $x, int $y = -1) {
		$this->scale['x'] = $x;
		$this->scale['y'] = $y;
	}
	
	protected function getShape() : string {
		return $this->shapeType;
	}

	public function shape(string $shapeType) {
		$this->shapeType = $shapeType;
	}

	protected function getContrast() : int {
		return $this->contrast;
	}

	/**
	 * Image constrast.
	 * Options: low, medium and hight.
	 * By default none.
	 */
	public function contrast(string $contrast) {

		switch($contrast) {
			case 'low':
				$contrastNumber = -10;
			break;
			case 'medium':
				$contrastNumber = -50;
			break;
			case 'hight':
				$contrastNumber = -80;
			break;
			default: 
				$contrastNumber = 0;
			break;
		}
		$this->contrast = $contrastNumber;
	}

	protected function getRequiredImage() : bool {
		return $this->requiredImage;
	}

	public function required() {
		$this->requiredImage = true;
	}

	protected function getPosition() : string {
		return $this->cropPosition;
	}

	/**
	 * Position for crop.
	 * Position center, left, right, top, bottom.
	 * @param string $cropPosition - Position type.
	 * @default center
	 */
	public function position(string $cropPosition) {
		$this->cropPosition = $cropPosition;

	}

	protected function getOldImageName() : string {
		return $this->oldImageName;
	}

	public function setOldImageName(string $oldImageName) {
		$this->oldImageName = $oldImageName;
	}

	protected function getConversionTo() : string {
		return $this->conversionTo;
	}

	public function conversionTo(string $conversionTo) {
		$this->conversionTo = $conversionTo;
	}

	protected function getAllowedFormats() : array {
		return $this->allowedFormats;
	}

	// FINAL GETS & SETS

	// MODIFY IMAGE

	private function cropPosition(array $pixelsImage) : array {
		
		$position = [
			'x' => 0,
			'y' => 0
		];

		switch($this->getPosition() ) {
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

		$dimensions = [
			'x' => imagesx($image),
			'y' => imagesy($image)
		];
		
		switch ($this->getShape() ) {
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

				$position = $this->cropPosition($dimensions);

				$min = min($dimensions['x'], $dimensions['y']);
				$dimensions['x'] = $min;
				$dimensions['y'] = $min;
			break;
			case 'h_rectangle':

				$heightRedimension = ceil(($dimensions['x'] / 161) * 100);
				$dimensions['y'] += ($dimensions['x'] - $heightRedimension) / 2;

				$position = $this->cropPosition($dimensions);
				$dimensions['y'] = $heightRedimension;
			break;
			case 'v_rectangle':

				$widthRedimension = ceil(($dimensions['y'] / 161) * 100);
				$dimensions['x'] += ($dimensions['y'] - $widthRedimension) / 2;

				$position = $this->cropPosition($dimensions);
				$dimensions['x'] = $widthRedimension;
			break;
			default:

				return $image;	
			break;
		}

		$croppedImage = imagecrop($image, [
			'x' => $position['x'],
			'y' => $position['y'],
			'width' => $dimensions['x'],
			'height' => $dimensions['y']
		]);
		
		return $croppedImage;
  }

  protected function scaleModify($image) {

		if($this->getScale()['x'] != -1 || $this->getScale()['y'] != -1) {

			$image = imagescale(
				$image, 
				$this->getScale()['x'], 
				$this->getScale()['y'], 
				IMG_BILINEAR_FIXED
			);
		}

    return $image;
  }

	protected function imageConverterUpload($image, string $target_file) : bool {

		return ('image'.$this->getConversionTo() )($image, $target_file);
	}

	// FINAL MODIFY IMAGE

	protected function imageUpload($image, string $target_file) : bool {

		return ($this->getConversionTo() != 'default') ? 
						$this->imageConverterUpload($image, $target_file) : 
						($this->transformImage)($image, $target_file);
	}

	// Contrast 
	protected function contrastModify($image) {

		imagefilter($image, IMG_FILTER_CONTRAST, $this->getContrast() );
		return $image;
	}

	// Verification configuration
	protected function verifyPath() : bool {

		return (is_dir($this->getPath() ) );
	}
	
	protected function error(string $message) {
		
		if($this->response['valid']) {
		
			$this->response['valid'] = false;
		}

		array_push($this->response['errors'], $message);
	}

}

?>