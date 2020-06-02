<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage;

use \Exception;
use Jonasdamher\Simplifyimage\Core\Validate;

/**
 * Upload, modify and delete images the easy way. 
 * 
 * Allows crop and scale images easy. 
 * Allows upload and update one or multiple images. 
 * Allows conversion format image to WEBP, PNG and JPEG.
 * 
 * @package simplifyimage
 * @version 0.1
 * @license https://github.com/jonasdamher/simplifyimage/blob/master/LICENSE MIT License
 * 
 * @author Jonás Damián Hernández [jonasdamher]
 */
class Image extends Validate
{

	private array $image;
	private string $pathSaveFile;
	private int $size;
	private string $format;

	private function getPropertiesImage()
	{
		$this->image = $_FILES[$this->getNameInputFile()];

		$pathAndImageName = $this->path->get() . $this->image['name'];

		$this->size = (int) $this->image['size'];
		$this->format = strtolower(pathinfo($pathAndImageName, PATHINFO_EXTENSION));

		$extesion = ($this->conversion->get() == 'default' ? $this->format : $this->conversion->get());

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
		$imgFormat = (!$this->crop->exist()) ? $this->crop->modify($imgFormat) : $imgFormat;
		// Image scale
		$imgFormat = $this->scale->modify($imgFormat);
		// Image contrast
		$imgFormat = $this->contrast->modify($imgFormat);

		$this->conversion->transform($imgFormat, $this->transformImage, $this->image);
	}

	/**
	 * Upload new image.
	 * @return array
	 */
	public function upload(): array
	{
		try {

			if (!$this->existFileAndPath()) {
				throw new \Exception();
			}

			$this->getPropertiesImage();

			if (!$this->validateImage($this->format, $this->size)) {
				throw new \Exception();
			}

			$this->modifyImage();
			
			// Verify by modify in image.
			
			if (!$this->response()['valid']) {
				throw new \Exception();
			}
			
			if (!$this->imageUpload($this->image, $this->pathSaveFile)) {
				throw new \Exception();
			}

			$this->setFilenameResponse($this->image['name']);
		} finally {
			return $this->response();
		}
	}

	/**
	 * Remove image in specify path.
	 * @return array
	 */
	public function remove(): array
	{
		try {
			if (!$this->verifyImagePath($this->getOldImageName())) {
				throw new \Exception("Don't exist image file.");
			}

			$imagePath = $this->path->get() . $this->getOldImageName();

			if (!unlink($imagePath)) {
				throw new \Exception("Don't remove image.");
			}
		} catch (\Exception $e) {
			parent::fail($e->getMessage());
		} finally {
			return $this->response();
		}
	}
}
