<?php
namespace Aquarium\Resources\State;


use Aquarium\Resources\Config;
use Objection\LiteSetup;
use Objection\LiteObject;


/**
 * @property string			$PackageName
 * @property ResourceSet	$Source
 * @property ResourceSet	$Target
 */
class PackageResourceSet extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'PackageName'	=> LiteSetup::createString(),
			'Source'		=> LiteSetup::createInstanceOf(ResourceSet::class),
			'Target'		=> LiteSetup::createInstanceOf(ResourceSet::class)
		];
	}
	
	
	/**
	 * @param PackageResourceSet $set
	 * @return bool
	 */
	public function isModified(PackageResourceSet $set)
	{
		return
			$this->Source->isModified($set->Source) || 
			$this->Target->isModified($set->Target);
	}
	
	public function setRootPath()
	{
		$this->Source->setRootPath(Config::instance()->directories()->ResourcesSourceDirs[0]);
		$this->Target->setRootPath(Config::instance()->directories()->CompiledResourcesDir);
	}
}