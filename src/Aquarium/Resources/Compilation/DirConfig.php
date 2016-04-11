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
	
	
	public function addSourceDir($source)
	{
		if (in_array($source, $this->ResourcesSourceDirs)) 
			throw new \Exception("Source directory already defined: [$source]");
		
		$this->_p->ResourcesSourceDirs[] = $source;
	}
}