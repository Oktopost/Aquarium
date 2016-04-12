<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Modules\Utils\ResourceMap;
use Aquarium\Resources\Modules\Utils\ResourceCollection;


interface IGulpAction
{
	/**
	 * @param string $directory
	 */
	public function setTargetDir($directory);
	
	/**
	 * Get map of the expected result when this action will be executed
	 * @param ResourceCollection $collection
	 * @return ResourceMap
	 */
	public function getMap(ResourceCollection $collection);
	
	/**
	 * @param ResourceCollection $collection
	 * @return \stdClass
	 */
	public function getCommand(ResourceCollection $collection);
}