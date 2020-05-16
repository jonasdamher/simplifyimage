<?php

namespace libimagephp\LibImage;

// require_once 'Validate.php';

use libimagephp\LibImageValidate\Validate;

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
class Image extends Validate
{

  private array $image;
  private string $pathSaveFile;
  private int $size;
  private string $format;

  // METHODS PRIVATES
  private function getPropertiesImage()
  {
    $this->image = $_FILES[$this->getNameInputFile()];

    $pathAndImageName = $this->path->get() . $this->image['name'];

    $this->size = $this->image['size'];
    $this->format = strtolower(pathinfo($pathAndImageName, PATHINFO_EXTENSION));

    $extesion = ($this->getConversionTo() == 'default' ? $this->format : $this->getConversionTo());

    $this->image['name'] = basename($this->rename($pathAndImageName) . '.' . $extesion);

    $this->pathSaveFile = $this->path->get() . $this->image['name'];
  }

  private function rename(string $path): string
  {
    $fileName = mb_strtolower(pathinfo($path, PATHINFO_FILENAME));
    $cleanFileName = $this->getPrefixName() . uniqid() . preg_replace('/\s+||[^a-zA-Z0-9-]/', '', $fileName);
    $filterFileName = filter_var($cleanFileName, FILTER_SANITIZE_STRING);

    return $filterFileName;
  }

  private function modifyImage()
  {
    // createImageFormat
    $imgFormat = ($this->imagecreatefrom)($this->image['tmp_name']);
    // Image crop
    $imgFormat = $this->crop->modify($imgFormat);
    // Image scale
    $imgFormat = $this->scale->modify($imgFormat);
    // Image contrast
    $imgFormat = $this->contrast->modify($imgFormat);

    return $imgFormat;
  }

  /**
   * Upload new image
   */
  public function upload(): array
  {

    if (!$this->exist()) {

      if ($this->getRequiredImage()) {
        $this->error("Don't exist image request.");
      }
      return $this->response;
    }

    $this->getPropertiesImage();

    if (!$this->validateImage(['format' => $this->format, 'size' => $this->size])) {
      return $this->response;
    }

    $this->transformImageTo($this->modifyImage(), $this->image);

    if (!$this->imageUpload($this->image, $this->pathSaveFile)) {
      return $this->response;
    }

    $this->response['filename'] = $this->image['name'];

    return $this->response;
  }

  public function remove(): array
  {
    if (!$this->verifyImagePath($this->getOldImageName())) {
      $this->error("Don't exist image file.");
      return $this->response;
    }

    $imagePath = $this->path->get() . $this->getOldImageName();

    if (!unlink($imagePath)) {
      $this->error("Don't remove image.");
    }
    return $this->response;
  }
}
?>