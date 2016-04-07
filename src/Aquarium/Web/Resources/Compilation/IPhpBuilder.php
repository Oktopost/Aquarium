<?php
namespace Aquarium\Web\Resources\Compilation;


use Aquarium\Web\Resources\Package\PackageDefinition;


interface IPhpBuilder
{
	/**
	 * @param PackageDefinition $definition
	 */
	public function buildPhpFile(PackageDefinition $definition);
}