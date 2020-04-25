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
	private bool $requiredImage = false;
	private int $maxSize = 2097152; // 2 MB
	private array $allowedFormats = [
		'png',
		'jpg',
		'jpeg',
		'gif',
		'webp'
	];

	// texto que se concatena con el tipo de imagen para 
	// conversión a webp * imagecreatefromjpeg	
	// establecer formato de imagen
	protected string $formatImage = 'imagecreatefrom'; //img formato por defecto
	// transformar imagen a otro formato
	protected string $transformImage = 'image'; // img transformacion a formato defecto

	// Image conversion to other format
	// @param string $conversionTo default, webp, png, jpeg, gif
	private string $conversionTo = 'default';

	/**
	 * Imagen antigua, testeando por ahora, variable prueba
	 * @example imagenAntigua.png
	 */
	private $oldImageName;

	// GETS & SETS

	/**
	 * Devuelve el nombre del input del formulario
	 */
	protected function getNameInputFile(): string
	{
		return $this->nameInputFile;
	}

	/**
	 * Nombre del input del formulario
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
	 * Head name file.
	 * 
	 * Not allows special simbols.
	 */
	public function prefixName(string $prefixName)
	{
		$this->prefixName = $prefixName;
	}

	/**
	 * Devuelve el tamaño máx permitido para subida de imagenes
	 */
	protected function getMaxSize(): int
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

	protected function getRequiredImage(): bool
	{
		return $this->requiredImage;
	}

	public function required()
	{
		$this->requiredImage = true;
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

	protected function getAllowedFormats(): array
	{
		return $this->allowedFormats;
	}

	// FINAL GETS & SETS

	public function __construct()
	{

		$this->path = new Path();
		$this->contrast = new Contrast();
		$this->scale = new Scale();

		$this->crop = new Crop();
	}


	public function transformImageTo($imageCreate, $imageArray)
	{
		return ($this->getConversionTo() != 'default') ?
			('image' . $this->getConversionTo())($imageCreate, $imageArray['tmp_name'])
			: ($this->transformImage)($imageCreate, $imageArray['tmp_name']);
	}

	// FINAL MODIFY IMAGE

	protected function imageUpload(array $image, string $target_file): bool
	{

		if (!is_uploaded_file($image['tmp_name']) || !move_uploaded_file($image['tmp_name'], $target_file)) {
			$this->error('It could not image upload, try again.');
			return false;
		}
		return true;
	}

	// Verification configuration

	protected function error(string $message)
	{

		if ($this->response['valid']) {

			$this->response['valid'] = false;
		}

		array_push($this->response['errors'], $message);
	}
}
