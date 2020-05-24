<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp;

use Jonasdamher\Libimagephp\Core\Validate;

/**
 * libimagephp
 * 
 * Upload, modify and delete images the easy way. 
 * 
 * Allows crop and scale images easy. 
 * Allows upload and update one or multiple images. 
 * Allows conversion format image to WEBP, PNG, JPG and JPEG.
 * 
 * @author Jonás Damián Hernández [jonasdamher]
 * 
 * @package libimagephp
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

    $this->size = (int) $this->image['size'];
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
   * 
   * @return array
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

    $propertiesValidate = ['format' => $this->format, 'size' => $this->size];

    if (!$this->validateImage($propertiesValidate)) {
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