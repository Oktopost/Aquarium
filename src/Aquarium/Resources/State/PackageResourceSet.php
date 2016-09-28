<?php
namespace Aquarium\Resources\State;


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
	
	
	public function isModified(PackageResourceSet $set)
	{
		return
			$this->Source->isModified($set->Source) || 
			$this->Target->isModified($set->Target);
	}
}