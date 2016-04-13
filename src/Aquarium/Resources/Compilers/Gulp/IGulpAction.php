<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceMap;
use Aquarium\Resources\Utils\ResourceCollection;


interface IGulpAction
{
	/**
	 * @param string $directory
	 */
	public function setTargetDir($directory);
	
	/**
	 * Get map of the expected result when this action will be executed
	 * @param Package $p
	 * @param ResourceCollection $collection
	 * @return ResourceMap
	 */
	public function getMap(Package $p, ResourceCollection $collection);
	
	/**
	 * @param Package $p
	 * @param ResourceCollection $collection
	 * @return array
	 */
	public function getCommand(Package $p, ResourceCollection $collection);
}