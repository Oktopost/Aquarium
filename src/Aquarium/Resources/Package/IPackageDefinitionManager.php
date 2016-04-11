<?php
namespace Aquarium\Resources\Package;


use Aquarium\Resources\Package;


interface IPackageDefinitionManager extends IPackagesCollection
{
	/**
	 * @param string $name
	 * @return Package
	 */
	public function get($name);
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name);
}