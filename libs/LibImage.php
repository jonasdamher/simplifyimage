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
* @package LibImage
* @version 1.0
*/

class LibImage extends LibImageConfiguration {

  /**
   * Array con las propiedades de imagen recogidas del formulario
   */
  private array $image;

  /**
   * Nombre de imagen con su extensión
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
   * Extensión de la imagen
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

    $unqid = uniqid();
    $fileName = strtolower( pathinfo( $path, PATHINFO_FILENAME) );
    $rename = $unqid.$fileName;
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

    if($this->postFileExist() ) {

      $this->image = $_FILES[$this->getNameInputFile()];
          
      $target = $this->getPath().$this->image['name'];

      $this->size = $this->image['size'];
      $this->type = strtolower(pathinfo( $target, PATHINFO_EXTENSION) );

      $this->fileName = $this->rename($target).'.webp';

      $this->target_file = $this->getPath().$this->fileName;
      
      return true;
    }

    return false;
  }

  // VALIDATE IMAGE

  private function format() {
    
    foreach ($this->getAllowedFormats() as $format) {
      if($format == $this->type) {
        $this->formatImage .= (($this->type == 'jpg') ? 'jpeg' : $this->type);
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
      $this->response['errors'] = 'Tiene que ser una imagen menor a 2 MB';
    }
    
    if(!($this->format() ) ) {
      $this->response['errors'] = 'El formato de imagen no es correcto.';
    }

    return $this->response['valid'];
  }

  // FINAL VALIDATE IMAGE


  public function uploadNewImage() {

    if($this->postImageFile() ) {

      
      if($this->validateImage() ) {

        // Ejecuta metodo dependiendo del formato de imagen
        $image = ($this->formatImage)($this->image['tmp_name']);

        // Escalar imagen
        $imageNew = $this->scale($image);

        if(!(imagewebp($imageNew, $this->target_file) ) ) {
      
          $this->response['valid'] = false;
          return $this->response['errors'] = 'La imagen no pudo subirse, intentelo de nuevo';
        }

        imageDestroy($imageNew);

        $this->response['filename'] = $this->fileName;
      
      }

    }

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