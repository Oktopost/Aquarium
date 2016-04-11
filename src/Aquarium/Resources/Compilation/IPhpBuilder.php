<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Package;


interface IPhpBuilder
{
	/**
	 * @param Package $p
	 */
	public function buildPhpFile(Package $p);
}