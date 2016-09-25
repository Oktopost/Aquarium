<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceMap;
use Aquarium\Resources\Utils\ResourceCollection;
use Aquarium\Resources\Compilers\Gulp\IGulpAction;
use Aquarium\Resources\Compilers\Gulp\GulpActionType;


class HandleBarAction implements IGulpAction
{
	const FILTER = '*.hbs.html';
	
	
	private $dir;
	
	
	/**
	 * @param Package $p
	 * @return string
	 */
	private function getPath(Package $p)
	{
		return 
			$this->dir . DIRECTORY_SEPARATOR . 
				$p->getName('_') . DIRECTORY_SEPARATOR . 
				substr(md5($p->getName()), 0, 8) . '.view.js';
	}
	
	
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected function getFileType() { return 'js'; }
	
	/**
	 * @return string
	 */
	protected function getActionType() { return GulpActionType::HANDLE_BARS; }
	
	
	/**
	 * @param string $directory
	 */
	public function setTargetDir($directory)
	{
		$this->dir = $directory;
	}
	
	/**
	 * @param Package $p
	 * @param ResourceCollection $collection
	 * @return ResourceMap
	 */
	public function getMap(Package $p, ResourceCollection $collection)
	{
		$sourceFiles = $collection->get(self::FILTER);
		$filePath = $this->getPath($p);
		
		$map = new ResourceMap();
		
		if ($sourceFiles)
		{
			$map->map($sourceFiles, $filePath);
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
		$resources = $collection->get(self::FILTER);
		$filePath = $this->getPath($p);
		
		return [
			'action'	=> $this->getActionType(),
			'source'	=> $resources,
			'target'	=> $filePath
		];
	}
}