<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Package;
use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property string $PhpTargetDir			Directory were all compiled PHP classes are stored.
 * @property string $CompiledResourcesDir	Directory were all compiled resources should be stored.
 * @property string	$RootWWWDirectory		After compilation all paths to the compiled resources are 
 * 											truncated to be relative to this path.
 * @property array	$ResourcesSourceDirs	Directories that contain the resources to compile.
 * @property string $StateFile				File used to keep truck of the compiled packages.
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
			'CompiledResourcesDir'	=> LiteSetup::createString(),
			'RootWWWDirectory'		=> LiteSetup::createString(),
			'ResourcesSourceDirs'	=> LiteSetup::createArray([], AccessRestriction::NO_SET),
			'StateFile'				=> LiteSetup::createString()
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
	 * @param Package $p
	 */
	public function truncateResourcesToPublicDir(Package $p)
	{
		$p->Scripts->truncatePath($this->RootWWWDirectory);
		$p->Styles->truncatePath($this->RootWWWDirectory);
		$p->Views->truncatePath($this->RootWWWDirectory);
	}
}