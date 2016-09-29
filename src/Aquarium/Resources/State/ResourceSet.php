<?php
namespace Aquarium\Resources\State;


use Objection\LiteSetup;
use Objection\LiteObject;


/**
 * @property ResourceFile[] $Files
 */
class ResourceSet extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Files' => LiteSetup::createInstanceArray(ResourceFile::class)
		];
	}
	
	
	/**
	 * @param ResourceSet $set
	 * @return bool
	 */
	public function isModified(ResourceSet $set)
	{
		$myFiles = $this->getResourcesByFullPath();
		$setFiles = $set->getResourcesByFullPath();
		
		if (array_diff_key($myFiles, $setFiles) || array_diff_key($setFiles, $myFiles))
			return true;
		
		foreach ($myFiles as $path => $file)
		{
			$setFile = $setFiles[$path];
			
			if ($file->isModified($setFile))
				return true;
		}
		
		return false;
	}
	
	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->Files);
	}
	
	/**
	 * @return array
	 */
	public function getFileNames()
	{
		return array_reduce(
			$this->Files,
			function($carry, ResourceFile $item) 
			{
				$carry[] = $item->Path;
				return $carry;
			},
			[]
		);
	}
	
	/**
	 * @return ResourceFile[]
	 */
	public function getResourcesByFullPath()
	{
		return array_reduce(
			$this->Files,
			function($carry, ResourceFile $item)
			{
				$carry[$item->Path] = $item;
				return $carry;
			},
			[]
		);
	}
	
	/**
	 * @param string $rootPath
	 */
	public function setRootPath($rootPath)
	{
		foreach ($this->Files as $file)
		{
			$file->setRootDir($rootPath);
		}
	}
}