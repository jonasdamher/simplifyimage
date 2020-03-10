<?php 

require_once 'LibImageConfiguration.php';

/**
* LibImage
*
* Subir, modificar y borrar imagenes de manera sencilla.
*
* Permite recortar y escalar imagenes facilmente.
* Permite subir o actualizar una o multiples imagenes.
* Permite hacer conversión de tipo de imagen a WEBP, PNG, JPG y JPEG.
*
* @author Jonás Damián Hernández [jonasdamher]
*
* @package LibImagePhp
* @version 1.0
*
*/
class LibImage extends LibImageConfiguration {

  /**
   * Array con las propiedades de imagen recogidas del formulario
   */
  private array $image;

  /**
   * Image name with extension.
   * @example imagen.png
   */
  private string $fileName;
  
  /**
   * Ruta completa donde se encuentra el archivo alojado
   */
  private string $target_file;
  
  /**
   * Tamaño que tiene la imagen
   * @example 10024
   */
  private int $size;
  
  /**
   * Image with extension type
   * @example png
   */
  private string $type;
  
  /**
   * Imagen antigua, testeando por ahora, variable prueba
   * @example imagenAntigua.png
   */
  private $oldImageName;

  // METHODS PRIVATES

  private function rename($path) {

    $fileName = strtolower( pathinfo($path, PATHINFO_FILENAME) );

    $cleanFileName  = preg_replace('/[^a-zA-Z0-9_ -]/','',$fileName);
    $cleanFileName  = preg_replace('/\s+/','',$cleanFileName);

    $rename = uniqid().$cleanFileName;
    $rename = filter_var($rename, FILTER_SANITIZE_STRING);

    return $rename;
  }

  // VERIFY IMAGE FILE

  private function postFileExist() {

    if(isset($_FILES[$this->getNameInputFile()]) && mb_strlen($_FILES[$this->getNameInputFile()]['tmp_name']) > 0 ) {

      return true;
    
    }
    
    return false;

  }

  private function postImageFile() {

    if(!($this->postFileExist() ) ) {
      return false;
    }

    $this->image = $_FILES[$this->getNameInputFile()];
          
    $target = $this->getPath().$this->image['name'];

    $this->size = $this->image['size'];
    $this->type = strtolower(pathinfo( $target, PATHINFO_EXTENSION) );

    $this->fileName = basename(
      $this->rename($target).'.'.($this->getConversionTo() == 'default' ? 
      $this->type :   
      $this->getConversionTo() ) 
    );

    $this->target_file = $this->getPath().$this->fileName;
    
    return true;
  }

  // VALIDATE IMAGE

  private function format() {
    
    foreach ($this->getAllowedFormats() as $format) {
      if($format == $this->type) {
        $this->formatImage .= (($this->type == 'jpg') ? 'jpeg' : $this->type);
        $this->transformImage .= (($this->type == 'jpg') ? 'jpeg' : $this->type);

        return true; 
      }
    }

    $this->response['valid'] = false;
    return false;
  }

  private function size() {
    if($this->size <= $this->getMaxSize() ) {                      
      return true;
    }

    $this->response['valid'] = false;
    return false;
  }

  private function validateImage() {

    if(!($this->size() ) ) {
      $this->response['errors'] = 'Tiene que ser una imagen menor a 2 MB.';
    }
    
    if(!($this->format() ) ) {
      $this->response['errors'] = 'Image format incorrect.';
    }

    return $this->response['valid'];
  }

  // FINAL VALIDATE IMAGE

  /**
   * Upload new image
   */
  public function uploadNewImage() {

    if(!($this->postImageFile() ) ) {
      
      if($this->getRequireImage() ) {

        $this->response['valid'] = false;
        $this->response['errors'] = 'Dont exist request image.';
      }

      return $this->response;
    }
      
    if(!($this->validateImage() ) ) {
      
      return $this->response;
    }

    // Le añade el formato original a la imagen
    $imageTo = ($this->formatImage)($this->image['tmp_name']);
  
    // Escalar imagen
    $myimage = $this->scale($imageTo);
    
    if(!($this->upload($myimage, $this->target_file) ) ) {

      $this->response['valid'] = false;
      $this->response['errors'] = 'La imagen no pudo subirse, intentelo de nuevo';
      
      return $this->response;
    }

    imageDestroy($myimage);

    $this->response['filename'] = $this->fileName;
    
    return $this->response;
  }

  public function uploadUpdateImage() {

    if($this->postImageFile() ) {

      if( $this->validateImage() ) {

        $image = ($this->formatImage)($this->image['tmp_name']);

        $imageNew = $this->scale($image);

        if(!(imagewebp($imageNew, $this->target_file) ) ) {
      
          $this->response['valid'] = false;
          return $this->response['errors'] = 'La imagen no pudo subirse, intentelo de nuevo';
        }

        imageDestroy($imageNew);

        if($this->typeUpload == 'update' && !is_null($this->oldImageName) ) {
          $this->destroyOldImage();
        }

        $this->response['filename'] = $this->fileName;
      
      }

    }

    return $this->response;
  }
  
  private function destroyOldImage() {
    
    $target = $this->getPath().$this->oldImageName;
    
    if(file_exists($target)) {
   
      unlink($target);
    }
  }

  }