<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Compilers\Gulp\GulpActionType;
use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceMap;
use Aquarium\Resources\Utils\ResourceCollection;
use Aquarium\Resources\Compilers\Gulp\IGulpAction;


abstract class AbstractGulpAction implements IGulpAction
{
	private $directory;
	private $filter = false;
	
	
	/**
	 * @param Package $p
	 * @param string $filePath
	 * @return string
	 */
	private function moveToTargetDirectory(Package $p, $filePath)
	{
		$path = $this->directory . DIRECTORY_SEPARATOR . $p->getName('_'); 
		
		if ($this->getFileType())
		{
			$nameStartPos = strrpos($filePath, DIRECTORY_SEPARATOR) + 1;
			$name = substr($filePath, $nameStartPos, strrpos($filePath, '.') - $nameStartPos);
			$name = "$name.{$this->getFileType()}";
			
			return $path . DIRECTORY_SEPARATOR . $name;
		}
		
		return $path . substr($filePath, strrpos($filePath, DIRECTORY_SEPARATOR)); 
	}
	
	/**
	 * @param Package $p
	 * @param array $sourceFiles
	 * @return string
	 */
	private function generateSingleFileName(Package $p, array $sourceFiles)
	{
		$targetFile = $this->directory . DIRECTORY_SEPARATOR . $p->getName('_');
		$first = reset($sourceFiles);
		$targetFile .= '/' . $p->getName('_') . substr($first, strrpos($first, '.'));
		
		return $targetFile;
	}
	
	
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected abstract function getFileType();
	
	/**
	 * @return string
	 */
	protected abstract function getActionType();
	
	
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
		
		if (!$sourceFiles) 
			return $map;
		
		if ($this->getActionType() == GulpActionType::CONCATENATE)
		{
			$map->map($sourceFiles, $this->generateSingleFileName($p, $sourceFiles));
		}
		else 
		{
			foreach ($sourceFiles as $filePath)
			{
				$map->map($filePath, $this->moveToTargetDirectory($p, $filePath));
			}
		}
		
		return $map;
	}
	
	/**
	 * @param Package $p
	 * @param ResourceCollection $collection
	 * @return array
	 */
	public function getCommand(Package $p, ResourceCollection $collection)
	{
		$resources = $collection->get($this->filter);
		
		$data = [
			'action'	=> $this->getActionType(),
			'source'	=> $collection->get($this->filter)
		];
		
		if ($this->getActionType() == GulpActionType::CONCATENATE)
		{
			$data['target'] = $this->generateSingleFileName($p, $resources);
		}
		
		return $data;
	}
}