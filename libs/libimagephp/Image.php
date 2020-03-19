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

    $cleanFileName  =  $this->getPrefixName().uniqid().preg_replace('/\s+||[^a-zA-Z0-9_ -]/','',$fileName);

    $newFileName = filter_var($cleanFileName, FILTER_SANITIZE_STRING);

    return $newFileName;
  }

  private function getPropertiesImage() {
    
    $this->image = $_FILES[$this->getNameInputFile()];
    
    $pathAndImageName = $this->getPath().$this->image['name'];

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

    $this->pathCacheFile = $this->getPath().$this->fileName;

  }

  // VERIFY IMAGE FILE

  private function postFileExist() :bool {

    return (
      isset($_FILES[$this->getNameInputFile()]) && 
      mb_strlen($_FILES[$this->getNameInputFile()]['tmp_name']) > 0
    );
  }

  private function postImageFile() : bool {

    if(!$this->verifyPath() ) {
      
      $this->error('Dont exist path, your path is ('.$this->getPath().').');
      return false;
    }

    if(!$this->postFileExist() ) {

      return false;
    }
    
    $this->getPropertiesImage();

    return true;
  }

  // VALIDATE IMAGE

  private function getFormatImage() : string {
    return ($this->format == 'jpg') ? 'jpeg' : $this->format;
  }

  private function formatValidate() : bool {
    
    foreach ($this->getAllowedFormats() as $AllowFormat) {

      if($this->format == $AllowFormat) {

        $this->formatImage .= $this->getFormatImage();
        $this->transformImage .= $this->getFormatImage();

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

      $this->error('It has to be an image smaller than '.$this->getMaxSize.' MB.');
    }
    
    if(!($this->formatValidate() ) ) {

      $this->error('Invalid image format.');
    }

    return $this->response['valid'];
  }

  // FINAL VALIDATE IMAGE

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
  public function updateImage() : array {

    if(!($this->verifyOldImage() ) ) {

      $this->error("Don't exist old image request.");

      return $this->response;
    }
    
    if(!($this->postImageFile() ) ) {

      if($this->getRequiredImage() ) {

        $this->error("Don't exist image request.");
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
    
    if(!($this->imageUpload($myimage, $this->pathCacheFile) ) ) {

      $this->error('It could not image upload, try again.');
      return $this->response;
    }

    imageDestroy($myimage);

    if(!$this->remove() ) {

      $this->error('Dont destroy old image, try again.');
      return $this->response;
    }

    $this->response['filename'] = $this->fileName;
    
    return $this->response;
  }
 
  /**
   * Methods for old images
   */
  protected function verifyOldImage() : bool {
    
    $imagePath = $this->getPath().$this->oldImageName;
    return (file_exists($imagePath) );
  }
  
  protected function remove() : bool {
    
    $imagePath = $this->getPath().$this->oldImageName;
    return unlink($imagePath);
  }

}

?>