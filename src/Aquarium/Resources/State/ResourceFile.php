<?php
namespace Aquarium\Resources\State;


use Objection\LiteObject;
use Objection\LiteSetup;


/**
 * @property string $Path
 * @property string $MD5
 * @property int	$FileSize
 */
class ResourceFile extends LiteObject
{
	private $rootDir = '';
	
	
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Path'		=> LiteSetup::createString(),
			'MD5'		=> LiteSetup::createString(''),
			'FileSize'	=> LiteSetup::createInt(-1)
		];
	}
	
	
	/**
	 * @return bool
	 */
	public function isPopulated()
	{
		return (bool)$this->MD5;
	}
	
	/**
	 * @return int
	 */
	public function populateSizeIfEmpty()
	{
		if (!$this->FileSize)
		{
			$this->FileSize = filesize($this->getFullPath());
		}
		
		return $this->FileSize;
	}
	
	/**
	 * @return string
	 */
	public function populateMD5IfEmpty()
	{
		if (!$this->MD5)
		{
			$this->MD5 = md5_file($this->getFullPath());
		}
		
		return $this->MD5;
	}
	
	/**
	 * @param ResourceFile $file
	 * @return bool
	 */
	public function isModified(ResourceFile $file)
	{
		if (!$this->isExists() || !$file->isExists())
			return true;
		
		if ($this->populateSizeIfEmpty() != $file->populateSizeIfEmpty())
			return true;
		
		if ($this->populateMD5IfEmpty() != $file->populateMD5IfEmpty())
			return true;
		
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function isExists()
	{
		return file_exists($this->getFullPath());
	}
	
	/**
	 * @return string
	 */
	public function getFullPath()
	{
		return $this->rootDir . '/' . $this->Path;
	}
	
	/**
	 * @param string $rootDir
	 */
	public function setRootDir($rootDir)
	{
		$this->rootDir = $rootDir;
	}
}