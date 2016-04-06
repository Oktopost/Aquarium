<?php
namespace Aquarium\Web\Resources\Package;


interface IConstructor
{
	/**
	 * @param $packageName
	 * @return PackageDefinition
	 */
	public function construct($packageName);
}