<?php
namespace Aquarium\Web\Resources\Compilation;


use Aquarium\Web\Resources\Package\PackageDefinition;


interface ICompiler
{
	/**
	 * @param PackageDefinition $definition
	 * @return PackageDefinition
	 */
	public function compile(PackageDefinition $definition);
}