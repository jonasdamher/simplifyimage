<?php 

namespace libimagephp\LibImage;

require_once 'Configuration.php';

use libimagephp\LibImageConfiguration\Configuration;

/**
 * LibImagePhp
 * 
 * Upload, modify and delete images the easy way. 
 * 
 * Allows crop and scale images easy. 
 * Allows upload and update one or multiple images. 
 * Allows conversion format image to WEBP, PNG, JPG and JPEG.
 * 
 * @author Jonás Damián Hernández [jonasdamher]
 * 
 * @package LibImagePhp
 * @version 1.0
 * 
*/
class Image extends Configuration {

  private array $image;
  private string $pathCacheFile;

  private string $fileName;
  private int $size;
  private string $format;
  
  // METHODS PRIVATES
  private function rename(string $path) : string {

    $fileName = mb_strtolower( pathinfo($path, PATHINFO_FILENAME) );

    $cleanFileName = $this->getPrefixName().uniqid().preg_replace('/\s+||[^a-zA-Z0-9_ -]/','',$fileName);

    $filterFileName = filter_var($cleanFileName, FILTER_SANITIZE_STRING);

    return $filterFileName;
  }

  private function getPropertiesImage() {
    
    $this->image = $_FILES[$this->getNameInputFile()];
    
    $pathAndImageName = $this->path->get().$this->image['name'];

    $this->size = $this->image['size'];
    $this->format = strtolower(pathinfo( $pathAndImageName, PATHINFO_EXTENSION) );

    $this->fileName = basename(

      $this->rename($pathAndImageName).'.'.
      (
        $this->getConversionTo() == 'default' ? 
        $this->format :   
        $this->getConversionTo() 
      ) 
    );

    $this->pathCacheFile = $this->path->get().$this->fileName;

  }

  // VERIFY IMAGE FILE

  private function postFileExist() :bool {

    return (
      isset($_FILES[$this->getNameInputFile()]) && 
      mb_strlen($_FILES[$this->getNameInputFile()]['tmp_name']) > 0
    );
  }

  private function postImageFile() : bool {

    if(!$this->path->exist() ) {
      
      $this->error('Dont exist path, your path is ('.$this->path->get().').');
      return false;
    }

    if(!$this->postFileExist() ) {

      return false;
    }
    
    $this->getPropertiesImage();

    return true;
  }

  // VALIDATE IMAGE

  private function formatValidate() : bool {
    
    foreach ($this->getAllowedFormats() as $AllowFormat) {

      if($this->format == $AllowFormat) {

        $myFormat = ($this->format == 'jpg') ? 'jpeg' : $this->format;
    
        $this->formatImage .= $myFormat;
        $this->transformImage .= $myFormat;

        return true; 
      }
    }

    return false;
  }

  private function sizeValidate() : bool {

    return ($this->size <= $this->getMaxSize() );
  }

  private function validateImage() : bool {

    if(!($this->sizeValidate() ) ) {

      $this->error('It has to be an image smaller than '.($this->getMaxSize/1000000).' MB.');
    }
    
    if(!($this->formatValidate() ) ) {

      $this->error('Invalid image format.');
    }

    return $this->response['valid'];
  }

  // FINAL VALIDATE IMAGE

  private function modifyImage() {
    
    // Add image format
    $imageTo = ($this->formatImage)($this->image['tmp_name']);

    // Image crop
    $imageCrop = $this->crop->modify($imageTo);

    // Image scale
    $imageScale = $this->scale->modify($imageCrop);

    // Image contrast
    $imageContrast = $this->contrast->modify($imageScale);

    return $imageContrast;
  }

  /**
   * Upload new image
   */
  public function upload() : array {

    if(!($this->postImageFile() ) ) {

      if($this->getRequiredImage() ) {

        $this->error("Don't exist image request.");
      }

      return $this->response;
    }

    if(!($this->validateImage() ) ) {
      
      return $this->response;
    }

    $image = $this->modifyImage();

    if(!($this->imageUpload($image, $this->pathCacheFile) ) ) {

      $this->error('It could not image upload, try again.');
      return $this->response;
    }

    imageDestroy($image);

    $this->response['filename'] = $this->fileName;
    
    return $this->response;
  }

  /**
   * Update image, replace image
   */
 
  /**
   * Methods for old images
   */
  private function verifyOldImage() : bool {
    
    $imagePath = $this->path->get().$this->oldImageName;
    return (file_exists($imagePath) );
  }
  
  protected function remove() : bool {
    
    $imagePath = $this->path->get().$this->oldImageName;
    return unlink($imagePath);
  }

}

?>