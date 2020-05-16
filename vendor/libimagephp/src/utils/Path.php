<?php

namespace libimagephp\LibImageUtils;

class Path
{

	private string $path = '';

	/**
	 * Devuelve la ruta donde se guardará la imagen
	 */
	public function get(): string
	{
		return $this->path;
	}

	/**
	 * Especificar ruta donde se guardan las imagenes
	 * @param string $path de directorio
	 *	@example public/images/
	 */
	public function set(string $path)
	{
		$this->path = $path;
	}

	public function exist(): bool
	{
		return (is_dir($this->get()));
	}
}
