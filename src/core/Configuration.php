<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage\Core;

use Jonasdamher\Simplifyimage\Core\ResponseHandler;
use Jonasdamher\Simplifyimage\Utils\Path;
use Jonasdamher\Simplifyimage\Utils\Contrast;
use Jonasdamher\Simplifyimage\Utils\Scale;
use Jonasdamher\Simplifyimage\Utils\Crop;
use Jonasdamher\Simplifyimage\Utils\Conversion;

class Configuration extends ResponseHandler
{

	public Path $path;
	public Contrast $contrast;
	public Scale $scale;
	public Crop $crop;
	public Conversion $conversion;

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

	private $oldImageName;

	public function __construct()
	{
		$this->path = new Path;
		$this->contrast = new Contrast;
		$this->scale = new Scale;
		$this->crop = new Crop;
		$this->conversion = new Conversion;
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

	// FINAL GETS & SETS

	/**
	 * Upload image in your path.
	 * @param array $image - Current Image for upload.
	 * @param string $target_file - Path where to save.
	 * @return bool
	 */
	protected function imageUpload(array $image, string $target_file): bool
	{
		try {
			$ok = true;
			if (!is_uploaded_file($image['tmp_name']) || !move_uploaded_file($image['tmp_name'], $target_file)) {
				throw new \Exception('It could not image upload, try again.');
			}
		} catch (\Exception $e) {
			$ok = false;
			parent::fail($e->getMessage());
		} finally {
			return $ok;
		}
	}

	protected function response(): array
	{
		return parent::arrayResponse();
	}

	protected function setFilenameResponse(string $filename)
	{
		parent::filename($filename);
	}
}
