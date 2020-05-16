<?php

namespace libimagephp\LibImageValidate;

// require_once 'Configuration.php';

use libimagephp\LibImageConfiguration\Configuration;

/**
 * LibImagePhp
 * 
 * Validate if required file, size and format image, path save image. 
 * 
 */
class Validate extends Configuration
{

	private bool $requiredImage = false;
	private int $maxSize = 2097152;
	private array $allowedFormats = [
		'png',
		'jpg',
		'jpeg',
		'gif',
		'webp'
	];

	// GETS & SETS
	protected function getRequiredImage(): bool
	{
		return $this->requiredImage;
	}

	/** Set required file */
	public function required()
	{
		$this->requiredImage = true;
	}

	private function getMaxSize(): int
	{
		return $this->maxSize;
	}

	/**
	 * Max size allow for upload images
	 *
	 * By default is 2097152 bytes (2 MB)
	 * @param int $maxSize 
	 */
	public function maxSize(int $maxSize)
	{
		$this->maxSize = $maxSize;
	}

	private function getAllowedFormats(): array
	{
		return $this->allowedFormats;
	}

	// FINAL GETS & SETS

	private function fileExist(): bool
	{
		return (isset($_FILES[$this->getNameInputFile()]) && mb_strlen($_FILES[$this->getNameInputFile()]['tmp_name']) > 0);
	}

	protected function exist(): bool
	{
		if (!$this->path->exist()) {
			$this->error('Dont exist path, your path is (' . $this->path->get() . ').');
			return false;
		}
		if (!$this->fileExist()) {
			return false;
		}
		return true;
	}

	private function sizeValidate(int $size): bool
	{
		return ($size <= $this->getMaxSize());
	}

	private function formatValidate(string $format): bool
	{
		foreach ($this->getAllowedFormats() as $AllowFormat) {
			if ($format == $AllowFormat) {
				$myFormat = ($format == 'jpg') ? 'jpeg' : $format;

				$this->imagecreatefrom .= $myFormat;
				$this->transformImage .= $myFormat;
				return true;
			}
		}
		return false;
	}

	protected function validateImage(array $properties): bool
	{
		if (!$this->sizeValidate($properties['size'])) {
			$this->error('It has to be an image smaller than ' . ($this->getMaxSize / 1000000) . ' MB.');
		}
		if (!$this->formatValidate($properties['format'])) {
			$this->error('Invalid image format.');
		}

		return $this->response['valid'];
	}

	protected function verifyImagePath(string $imageName): bool
	{
		$imagePath = $this->path->get() . $imageName;
		return (file_exists($imagePath));
	}
}
?>