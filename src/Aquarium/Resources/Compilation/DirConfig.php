<?php
namespace Aquarium\Resources\Compilation;


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
}