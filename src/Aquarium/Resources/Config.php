<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Compilation\ICompiler;
use Aquarium\Resources\Compilation\DirConfig;
use Aquarium\Resources\Package\IPackageDefinitionManager;

use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property IConsumer 					$Consumer
 * @property IProvider					$Provider
 * @property IPackageDefinitionManager 	$DefinitionManager
 * @property DirConfig 					$Directories
 * @property ICompiler 					$Compiler
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
			'Consumer'				=> LiteSetup::createInstanceOf(IConsumer::class), 
			'Provider'				=> LiteSetup::createInstanceOf(IProvider::class), 
			'DefinitionManager'		=> LiteSetup::createInstanceOf(IPackageDefinitionManager::class),
			'Directories'			=> LiteSetup::createInstanceOf(new DirConfig(), AccessRestriction::NO_SET),
			'Compiler'				=> LiteSetup::createInstanceOf(ICompiler::class)
		];
	}
}