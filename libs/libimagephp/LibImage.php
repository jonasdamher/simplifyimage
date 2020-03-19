<?php 

namespace libimagephp\image;

require_once 'LibImageConfiguration.php';

use libimagephp\config\LibImageConfiguration;

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

  // METHODS PRIVATES

  private function rename(string $path) {

    $fileName = mb_strtolower( pathinfo($path, PATHINFO_FILENAME) );

    $cleanFileName  =  $this->getPrefixName().uniqid().preg_replace('/\s+||[^a-zA-Z0-9_ -]/','',$fileName);

    $newFileName = filter_var($cleanFileName, FILTER_SANITIZE_STRING);

    return $newFileName;
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
          
    $pathAndImageName = $this->getPath().$this->image['name'];

    $this->size = $this->image['size'];
    $this->type = strtolower(pathinfo( $pathAndImageName, PATHINFO_EXTENSION) );

    $this->fileName = basename(

      $this->rename($pathAndImageName).'.'.
      (
        $this->getConversionTo() == 'default' ? 
        $this->type :   
        $this->getConversionTo() 
      ) 
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
      $this->response['errors'] = 'It has to be an image smaller than '.$this->getMaxSize.' MB.';
    }
    
    if(!($this->format() ) ) {
      $this->response['errors'] = 'Invalid image format.';
    }

    return $this->response['valid'];
  }

  // FINAL VALIDATE IMAGE

  /**
   * Upload new image
   */
  public function upload() {

    if(!($this->postImageFile() ) ) {

      if($this->getRequiredImage() ) {

        $this->response['valid'] = false;
        $this->response['errors'] = "Don't exist image request.";
      }

      return $this->response;
    }

    if(!($this->validateImage() ) ) {
      
      return $this->response;
    }

    // Add image format
    $imageTo = ($this->formatImage)($this->image['tmp_name']);
  
    // Image scale
    $imageScale = $this->scaleModify($imageTo);
    
    // Image crop
    $imageCrop = $this->getShape() != 'default' ? 
    $this->crop($imageScale) : 
    $imageScale;

    $image = $this->getContrast() != 0 ? 
    $this->contrastModify($imageCrop) : 
    $imageCrop;

    if(!($this->upload($image, $this->target_file) ) ) {

      $this->response['valid'] = false;
      $this->response['errors'] = 'It could not image upload, try again.';
      
      return $this->response;
    }

    imageDestroy($image);

    $this->response['filename'] = $this->fileName;
    
    return $this->response;
  }

  /**
   * Update image, replace image
   */
  public function updateImage() {

    if(!($this->verifyOldImage() ) ) {

      $this->response['valid'] = false;
      $this->response['errors'] = "Don't exist old image request.";

      return $this->response;
    }
    
    if(!($this->postImageFile() ) ) {

      if($this->getRequiredImage() ) {

        $this->response['valid'] = false;
        $this->response['errors'] = "Don't exist image request.";
      }

      return $this->response;
    }

    if(!($this->validateImage() ) ) {
      
      return $this->response;
    }

    // Add image format
    $imageTo = ($this->formatImage)($this->image['tmp_name']);
  
    // Image scale
    $myimage = $this->scale($imageTo);
    
    if(!($this->upload($myimage, $this->target_file) ) ) {

      $this->response['valid'] = false;
      $this->response['errors'] = 'It could not image upload, try again.';
      
      return $this->response;
    }

    imageDestroy($myimage);

    if(!$this->destroyOldImage() ) {

      $this->response['valid'] = false;
      $this->response['errors'] = 'Dont destroy old image, try again.';
      
      return $this->response;
    }

    $this->response['filename'] = $this->fileName;
    
    return $this->response;
  }
 
  /**
   * Methods for old images
   */
  protected function verifyOldImage() {
    
    $imagePath = $this->getPath().$this->oldImageName;
    
    if(file_exists($imagePath)) {
   
      return true;
    }else {
      return false;
    }
  }
  
  protected function destroyOldImage() {
    
    $imagePath = $this->getPath().$this->oldImageName;
    
    return unlink($imagePath);
  }

  }