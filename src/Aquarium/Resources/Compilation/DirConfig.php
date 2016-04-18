<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Package;
use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property string $PhpTargetDir			Directory were all compiled PHP classes are stored.
 * @property string $ResourcesTargetDir		Directory were all compiled resources should be stored.
 * @property array	$ResourcesSourceDirs	Directories that contain the resources to compile.
 */
class DirConfig extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'PhpTargetDir'			=> LiteSetup::createString(),
			'ResourcesTargetDir'	=> LiteSetup::createString(),
			'ResourcesSourceDirs'	=> LiteSetup::createArray([], AccessRestriction::NO_SET)
		];
	}
	
	
	/**
	 * @param string $source
	 */
	public function addSourceDir($source)
	{
		if (in_array($source, $this->ResourcesSourceDirs)) return;
		
		$this->_p->ResourcesSourceDirs[] = $source;
	}
	
	/**
	 * @param string $source
	 * @return string|bool
	 */
	public function getPathToSource($source)
	{
		foreach ($this->ResourcesSourceDirs as $dir) 
		{
			$fullPath = DIRECTORY_SEPARATOR . join(
				DIRECTORY_SEPARATOR, 
				[
					trim($dir, DIRECTORY_SEPARATOR), 
					trim($source, DIRECTORY_SEPARATOR)
				]
			);
			
			if (file_exists($fullPath)) return $fullPath;
		}
		
		return false;
	}
	
	/**
	 * @param string $source
	 * @return string|bool
	 */
	public function getRelativePathToSource($source)
	{
		$options = array_merge(
			[$this->ResourcesTargetDir],
			$this->ResourcesSourceDirs
		);
		
		foreach ($options as $path)
		{
			if (strpos($source, $path) === 0)
			{
				$source = substr($source, strlen($path));
				
				if ($source[0] == DIRECTORY_SEPARATOR)
					return substr($source, 1);
				
				return $source;
			}
		}
		
		return false;
	}
	
	/**
	 * @param Package $p
	 */
	public function getRelativePathToPackageResources(Package $p)
	{
		foreach ($p->Scripts as $script)
		{
			$relative = $this->getRelativePathToSource($script);
			
			if ($relative) $p->Scripts->replace($script, $relative);
		}
		
		foreach ($p->Styles as $style)
		{
			$relative = $this->getRelativePathToSource($style);
			
			if ($relative) $p->Styles->replace($style, $relative);
		}
	}
}