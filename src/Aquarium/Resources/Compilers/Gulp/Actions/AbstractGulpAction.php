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
	 * @param string $filePath
	 * @return string
	 */
	private function moveToTargetDirectory($filePath)
	{
		return 
			$this->directory . DIRECTORY_SEPARATOR . 
			substr($filePath, strrpos($filePath, DIRECTORY_SEPARATOR)); 
	}
	
	
	/**
	 * @return bool Will this action result in a single file.
	 */
	protected abstract function isSingleFile();
	
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected abstract function getFileType();
	
	/**
	 * @return string
	 */
	protected abstract function getActionType();
	
	/**
	 * @param string $sourceName
	 * @return string New name after compilation.
	 */
	protected function rename($sourceName) { return $sourceName; }
	
	
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
		$map = new ResourceMap();
		$sourceFiles = $collection->get($this->filter);
		
		if ($this->isSingleFile())
		{
			$targetFile = $this->directory . DIRECTORY_SEPARATOR . $p->Name . '.' . $this->getFileType();
			$targetFile = $this->rename($targetFile);
			
			$map->map($sourceFiles, $targetFile);
		}
		else 
		{
			foreach ($sourceFiles as $filePath)
			{
				$newFilePath = $this->rename($this->moveToTargetDirectory($filePath));
				$map->map($filePath, $newFilePath);
			}
		}
		
		return $map;
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