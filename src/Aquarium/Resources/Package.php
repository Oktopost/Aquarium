<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Utils\ResourceCollection;

use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property string 			$Name
 * @property array				$Path
 * @property ResourceCollection	$Requires
 * @property ResourceCollection	$Inscribed
 * @property ResourceCollection	$Styles
 * @property ResourceCollection	$Scripts
 */
class Package extends LiteObject
{
	const PACKAGE_PATH_SEPARATOR = '/';
	
	
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Name'		=> LiteSetup::createString('', AccessRestriction::NO_SET),
			'Path'		=> LiteSetup::createArray([], AccessRestriction::NO_SET),
			'Requires'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET),
			'Inscribed'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET),
			'Styles'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET),
			'Scripts'	=> LiteSetup::createInstanceOf(ResourceCollection::class, AccessRestriction::NO_SET)
		];
	}
	
	
	/**
	 * @param string $name
	 */
	public function __construct($name) 
	{
		parent::__construct();
		
		$this->_p->Name			= $name;
		$this->_p->Path			= explode(self::PACKAGE_PATH_SEPARATOR, $name);
		$this->_p->Requires 	= new ResourceCollection();
		$this->_p->Inscribed	= new ResourceCollection();
		$this->_p->Styles		= new ResourceCollection();
		$this->_p->Scripts		= new ResourceCollection();
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
	
	/**
	 * @param string $glue
	 * @return string
	 */
	public function getName($glue = '/') 
	{
		return implode($glue, $this->Path);
	}
	
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function isValidPackageName($name)
	{
		$allowed = 'a-z0-9';
		$separator = Package::PACKAGE_PATH_SEPARATOR;
		return (bool)preg_match("/^[$allowed]+(\\{$separator}[$allowed]+)*$/i", $name);
	}
}