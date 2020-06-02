<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage\Core;

use Jonasdamher\Simplifyimage\Core\Configuration;

/**
 * Validate if required file, 
 * size and format image, path save image. 
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
	 * Max size allow for upload images.
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

	protected function existFileAndPath(): bool
	{
		try {
			$ok = true;
			if (!$this->path->exist()) {
				throw new \Exception('Dont exist path, your path is (' . $this->path->get() . ').');
			}
			if (!$this->fileExist()) {

				if ($this->getRequiredImage()) {
					throw new \Exception("Don't exist image request.");
				}
			}
		} catch (\Exception $e) {
			$ok = false;
			parent::fail($e->getMessage());
		} finally {
			return $ok;
		}
	}

	private function sizeValidate(int $size): bool
	{
		return $size <= $this->getMaxSize();
	}

	private function formatValidate(string $format): bool
	{
		if (!in_array($format, $this->getAllowedFormats(), true)) {
			return false;
		}
		$myFormat = ($format == 'jpg') ? 'jpeg' : $format;

		$this->imagecreatefrom .= $myFormat;
		$this->transformImage .= $myFormat;
		return true;
	}

	protected function validateImage(string $format, int $size): bool
	{
		try {
			$ok = true;
			if (!$this->sizeValidate($size)) {
				throw new \Exception('It has to be an image smaller than ' . $this->getMaxSize() . ' Bytes.');
			}
			if (!$this->formatValidate($format)) {
				throw new \Exception('Invalid image format.');
			}
		} catch (\Exception $e) {
			$ok = false;
			Parent::fail($e->getMessage());
		} finally {
			return $ok;
		}
	}

	protected function verifyImagePath(string $imageName): bool
	{
		$imagePath = $this->path->get() . $imageName;
		return (file_exists($imagePath));
	}
}
