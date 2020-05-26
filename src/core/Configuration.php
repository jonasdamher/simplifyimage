<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Core;

use Jonasdamher\Libimagephp\Utils\Path;
use Jonasdamher\Libimagephp\Utils\Contrast;
use Jonasdamher\Libimagephp\Utils\Scale;
use Jonasdamher\Libimagephp\Utils\Crop;

class Configuration
{

	protected array $response = [
		'valid' => true,
		'filename' => null,
		'errors' => []
	];

	public Path $path;
	public Contrast $contrast;
	public Scale $scale;
	public Crop $crop;

	private string $nameInputFile = '';
	private string $prefixName = '';

	/**
	 * to create the image from its default format.
	 */
	protected string $imagecreatefrom = 'imagecreatefrom';
	/**
	 * to transform the image to its default format.
	 */
	protected string $transformImage = 'image';
	private string $conversionTo = 'default';

	private $oldImageName;

	public function __construct()
	{
		$this->path = new Path;
		$this->contrast = new Contrast;
		$this->scale = new Scale;
		$this->crop = new Crop;
	}

	// GETS & SETS
	/**
	 * Return name form input.
	 */
	protected function getNameInputFile(): string
	{
		return $this->nameInputFile;
	}

	/**
	 * Set name form input.
	 * @param string $nameInputFile
	 */
	public function nameImputFile(string $nameInputFile)
	{
		$this->nameInputFile = $nameInputFile;
	}

	protected function getPrefixName(): string
	{
		return $this->prefixName;
	}

	/**
	 * Header for filename.
	 * Not allow special simbols.
	 * @param string $prefixName
	 */
	public function prefixName(string $prefixName)
	{
		$this->prefixName = $prefixName;
	}

	protected function getOldImageName(): string
	{
		return $this->oldImageName;
	}

	/**
	 * Experimental, for remove image.
	 * @example imageForRemove.png
	 */
	public function setOldImageName(string $oldImageName)
	{
		$this->oldImageName = $oldImageName;
	}

	protected function getConversionTo(): string
	{
		return $this->conversionTo;
	}

	public function conversionTo(string $conversionTo)
	{
		$this->conversionTo = $conversionTo;
	}

	// FINAL GETS & SETS

	public function transformImageTo($imagecreatefrom, array $imageArray)
	{
		return ($this->getConversionTo() != 'default') ?
			('image' . $this->getConversionTo())($imagecreatefrom, $imageArray['tmp_name']) : ($this->transformImage)($imagecreatefrom, $imageArray['tmp_name']);
	}

	/**
	 * Upload image in your path.
	 * @param array $image - Current Image for upload.
	 * @param string $target_file - Path where to save.
	 * @return bool
	 */
	protected function imageUpload(array $image, string $target_file): bool
	{
		if (!is_uploaded_file($image['tmp_name']) || !move_uploaded_file($image['tmp_name'], $target_file)) {
			$this->error('It could not image upload, try again.');
			return false;
		}
		return true;
	}

	/** 
	 * Set errors and save in array called $response.
	 * 
	 * @param string $message - Error description.
	 */
	protected function error(string $message)
	{
		if ($this->response['valid']) {
			$this->response['valid'] = false;
		}
		array_push($this->response['errors'], $message);
	}
}