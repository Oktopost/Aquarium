<?php
namespace Aquarium\Web\Resources;


use Aquarium\Web\Resources\Package\IConstructor;
use Aquarium\Web\Resources\Package\IPackageDefinitionManager;

use Objection\LiteSetup;
use Objection\LiteObject;


/**
 * @property IProvider 					$Provider
 * @property IConsumer 					$Consumer
 * @property IConstructor			 	$PackageConstructor
 * @property IPackageDefinitionManager 	$DefinitionManager
 */
class Config extends LiteObject
{
	use \Objection\TSingleton;
	
	
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Provider'				=> LiteSetup::createInstanceOf(IProvider::class), 
			'Consumer'				=> LiteSetup::createInstanceOf(IConsumer::class), 
			'PackageConstructor'	=> LiteSetup::createInstanceOf(IConstructor::class), 
			'DefinitionManager'		=> LiteSetup::createInstanceOf(IPackageDefinitionManager::class)	 
		];
	}
}