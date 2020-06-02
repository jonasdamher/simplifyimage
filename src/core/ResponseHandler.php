<?php

declare(strict_types=1);

namespace Jonasdamher\Simplifyimage\Core;

class ResponseHandler
{
	private static array $response = [
		'valid' => true,
		'filename' => null,
		'errors' => []
	];

	/** 
	 * Set errors and save in array called $response.
	 * @param string $message - Error description.
	 */
	public static function fail(string $message)
	{
		if (self::$response['valid']) {
			self::$response['valid'] = false;
		}
		array_push(self::$response['errors'], $message);
	}

	protected static function filename(string $name)
	{
		self::$response['filename'] = $name;
	}

	protected static function arrayResponse(): array
	{
		return self::$response;
	}
}
