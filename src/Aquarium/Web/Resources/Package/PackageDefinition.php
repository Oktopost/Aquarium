<?php
namespace Aquarium\Web\Resources\Package;


use Aquarium\Web\Resources\Package;

use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property Package	$Package
 */
class PackageDefinition extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Package'	=> LiteSetup::createInstanceOf(Package::class, AccessRestriction::NO_SET)
		];
	}
	
	
	/**
	 * @param Package $package
	 */
	public function __construct(Package $package)
	{
		parent::__construct(['Package' => $package]);
	}
}