<?php
namespace Aquarium\Web\Resources;


use Aquarium\Web\Resources\Utils\PackageUtils;
use Aquarium\Web\Resources\Utils\ResourceCollection;

use Objection\Enum\AccessRestriction;
use Objection\LiteSetup;
use Objection\LiteObject;


/**
 * @property string 			$Name
 * @property array				$Path
 * @property ResourceCollection	$Packages
 * @property ResourceCollection	$Styles
 * @property ResourceCollection	$Scripts
 */
class Package extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Name'		=> LiteSetup::createString('', AccessRestriction::NO_SET),
			'Path'		=> LiteSetup::createArray([], AccessRestriction::NO_SET),
			'Packages'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET),
			'Styles'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET),
			'Scripts'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET)
		];
	}
	
	
	/**
	 * @param $name
	 */
	public function __construct($name) 
	{
		parent::__construct([
			'Name'		=> $name,
			'Path'		=> explode(PackageUtils::PACKAGE_PATH_SEPARATOR, $name),
			'Packages'	=> new ResourceCollection(),
			'Styles'	=> new ResourceCollection(),
			'Scripts'	=> new ResourceCollection()
		]);
	}
	
	
	/**
	 * @param int|bool $at
	 * @return array
	 */
	public function getPath($at = false) 
	{
		if ($at === false) return $this->Path;
		
		return (
			isset($this->Path[$at]) ? 
				$this->Path[$at] : 
				null);
	}
}