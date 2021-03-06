<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceCollection;

use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property Package			$Package		Original compiled package
 * @property ResourceCollection	$Unchanged		Finale compiled source files that don't need to be recompiled.
 * @property ResourceCollection	$CompileTarget	Source files that should be compiled.
 */
class CompilerSetup extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Package'		=> LiteSetup::createInstanceOf(Package::class, AccessRestriction::NO_SET),
			'Unchanged'		=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET),
			'CompileTarget'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET)
		];
	}
	
	
	/**
	 * @param Package $p
	 */
	public function __construct(Package $p)
	{
		parent::__construct();
		
		$this->_p->Package			= $p;
		$this->_p->Unchanged		= new ResourceCollection();
		$this->_p->CompileTarget	= new ResourceCollection();
	}
	
	
	/**
	 * Is there any files that should be compiled.
	 * @return bool
	 */
	public function hasToCompile()
	{
		return $this->CompileTarget->hasAny();
	}
}