<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Modules\Utils\ResourceCollection;
use Aquarium\Resources\Modules\Utils\ResourceMap;
use Aquarium\Resources\Compilers\Gulp\IGulpAction;
use Aquarium\Resources\Package;


abstract class AbstractGulpAction implements IGulpAction
{
	private $directory;
	private $filter = false;
	
	
	/**
	 * @return bool Will this action result in a single file.
	 */
	protected abstract function isSingleFile();
	
	
	/**
	 * @param string $filter
	 */
	public function setFilter($filter)
	{
		$this->filter = $filter;
	}
	
	
	/**
	 * @param string $directory
	 */
	public function setTargetDir($directory)
	{
		$this->directory = $directory;
	}
	
	/**
	 * Get map of the expected result when this action will be executed
	 * @param Package $p
	 * @param ResourceCollection $collection
	 * @return ResourceMap
	 */
	public function getMap(Package $p, ResourceCollection $collection)
	{
		$sourceFiles = $collection->get($this->filter);
		
	}
	
	/**
	 * @param Package $p
	 * @param ResourceCollection $collection
	 * @return \stdClass
	 */
	public function getCommand(Package $p, ResourceCollection $collection)
	{
		$sourceFiles = $collection->get($this->filter);
	}
}