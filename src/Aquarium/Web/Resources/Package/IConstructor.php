<?php
namespace Aquarium\Web\Resources\Package;


interface IConstructor
{
	/**
	 * @param $packageName
	 * @return PackageDefinition
	 */
	public function construct($packageName);
	
	/**
	 * Construct the package and append it to the consumer.
	 * @param $packageName
	 */
	public function append($packageName);
}