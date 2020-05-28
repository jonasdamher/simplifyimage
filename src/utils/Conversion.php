<?php

declare(strict_types=1);

namespace Jonasdamher\Libimagephp\Utils;

use Jonasdamher\Libimagephp\Core\ResponseHandler;

class Conversion
{
	private string $conversionTo = 'default';
	private array $allowFormatConversion = ['webp','png','jpeg'];
	
	public function get(): string
	{
		return $this->conversionTo;
	}

	public function set(string $conversionTo)
	{
		try {
			if (!in_array($conversionTo, $this->allowFormatConversion, true)) {
				throw new \Exception("Don't exist format to conversion (" . $conversionTo . ')');
			}
		} catch (\Exception $e) {
			$conversionTo = 'png';
			ResponseHandler::fail($e->getMessage());
		} finally {
			$this->conversionTo = $conversionTo;
		}
	}

	public function transform($imagecreatefrom, string $transformImage, array $imageArray)
	{
		return ($this->get() != 'default') ?
			('image' . $this->get())($imagecreatefrom, $imageArray['tmp_name']) : ($transformImage)($imagecreatefrom, $imageArray['tmp_name']);
	}
}
