<?php 

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
		'webp'
	];

	private int $maxSize = 2097152 ; // 2 MB

	private array $scale = [
		'x' => 128, 
		'y' => -1
	];

	//Forma de recortar la imagen, square, v_rectangle, h_rectangle, default
	private string $cropType = 'default';

	// texto que se concatena con el tipo de imagen para conversión a webp * imagecreatefromjpeg
	protected string $formatImage = 'imagecreatefrom'; 

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

	protected function getAllowedFormats() {
		return $this->allowedFormats;
	}

	public function setAllowedFormats(string $allowedFormats) {
		$this->allowedFormats = $allowedFormats;
	}

	// MODIFY IMAGE

	protected function crop($image) {

    
    $crop_width = imagesx($image);
		$crop_height = imagesy($image);

    // coge el numero mas bajo de los dos
    $size = min($crop_width, $crop_height);

    $coordinates = [
      'x' => 0,
      'y' => 0
    ];
    
    if($this->getCropType() == 'square') {

      ($crop_width >= $crop_height) ? 
      $coordinates['x'] = ($crop_width-$crop_height)/2 :
      $coordinates['y'] = ($crop_height-$crop_width)/2;
      
      $cropped = imagecrop($image, [
        'x' => $coordinates['x'], 
        'y' => $coordinates['y'], 
        'width' => $size, 
        'height' => $size
      ]);
    }

    return $cropped;
  }

  protected function scale($image) {

    $image = $this->getCropType() != 'default' ? $this->crop($image) : $image;

    $imageNew = imagescale(
      $image, 
      $this->getScale()['x'], 
      $this->getScale()['y'], 
      IMG_BILINEAR_FIXED
    );

    return $imageNew;
  }

	// FINAL MODIFY IMAGE

}

?>