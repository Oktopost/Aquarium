<?php
namespace Aquarium\Web\Resources\ConstructorStrategy;


use Aquarium\Web\Resources\Package\IConstructor;
use Aquarium\Web\Resources\Package\PackageDefinition;


class SimpleConstructor implements IConstructor
{
	
	/**
	 * @param $packageName
	 * @return PackageDefinition
	 */
	public function construct($packageName)
	{
		return null;
	}
}