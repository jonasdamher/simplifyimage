<?php

namespace libimagephp\LibImageConfiguration;

require_once 'utils/Path.php';
require_once 'utils/Contrast.php';
require_once 'utils/Scale.php';
require_once 'utils/Crop.php';

use libimagephp\LibImageUtils\Path;
use libimagephp\LibImageUtils\Contrast;
use libimagephp\LibImageUtils\Scale;
use libimagephp\LibImageUtils\Crop;

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
	private string $prefixName =  '';

	protected string $imagecreatefrom = 'imagecreatefrom'; //img formato por defecto
	protected string $transformImage = 'image'; // img transformacion a formato defecto
	private string $conversionTo = 'default';

	/**
	 * Imagen antigua, testeando por ahora, variable prueba
	 * @example imagenAntigua.png
	 */
	private $oldImageName;

	public function __construct()
	{
		$this->path = new Path();
		$this->contrast = new Contrast();
		$this->scale = new Scale();
		$this->crop = new Crop();
	}

	// GETS & SETS
	/**
	 * Return name form input
	 */
	protected function getNameInputFile(): string
	{
		return $this->nameInputFile;
	}

	/**
	 * Set name form input
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
	 * Header name file.
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
			('image' . $this->getConversionTo())($imagecreatefrom, $imageArray['tmp_name']) : 
			($this->transformImage)($imagecreatefrom, $imageArray['tmp_name']);
	}


	protected function imageUpload(array $image, string $target_file): bool
	{
		if (!is_uploaded_file($image['tmp_name']) || !move_uploaded_file($image['tmp_name'], $target_file)) {
			$this->error('It could not image upload, try again.');
			return false;
		}
		return true;
	}

	/** Set errors and save in array called $response */
	protected function error(string $message)
	{
		if ($this->response['valid']) {
			$this->response['valid'] = false;
		}
		array_push($this->response['errors'], $message);
	}
}
