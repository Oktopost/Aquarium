<?php
namespace Aquarium\Web\Resources\Compilation;


use Aquarium\Web\Resources\Package;


interface IPhpBuilder
{
	/**
	 * @param Package $p
	 */
	public function buildPhpFile(Package $p);
}