<?php
namespace Aquarium\Web\Resources\Package;


use Aquarium\Web\Resources\Package;

use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property Package 	$Package Original package definition.
 * @property array		$Packages
 * @property array		$Scripts
 * @property array		$Styles
 */
class PackageDefinition extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Package'	=> LiteSetup::createInstanceOf(Package::class, AccessRestriction::NO_SET),
			'Packages'	=> LiteSetup::createArray([], AccessRestriction::NO_SET),
			'Scripts'	=> LiteSetup::createArray([], AccessRestriction::NO_SET),
			'Styles'	=> LiteSetup::createArray([], AccessRestriction::NO_SET)
		];
	}
	
	
	/**
	 * @param Package $package
	 */
	public function __construct(Package $package)
	{
		parent::__construct();
		$this->_p->Package = $package;
		$this->_p->Packages = [$package->Name]; 
	}
	
	
	/**
	 * @param string $package
	 * @return bool
	 */
	public function hasPackage($package)
	{
		return in_array($package, $this->Packages);
	}
	
	/**
	 * @param string $script
	 * @return bool
	 */
	public function hasScript($script)
	{
		return in_array($script, $this->Scripts);
	}
	
	/**
	 * @param string $style
	 * @return bool
	 */
	public function hasStyle($style)
	{
		return in_array($style, $this->Styles);
	}
}