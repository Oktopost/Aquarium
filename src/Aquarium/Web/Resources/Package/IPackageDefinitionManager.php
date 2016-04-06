<?php
namespace Aquarium\Web\Resources\Package;


use Aquarium\Web\Resources\Package;


interface IPackageDefinitionManager
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