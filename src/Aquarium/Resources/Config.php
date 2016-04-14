<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Package\IPackageDefinitionManager;
use Aquarium\Resources\Compilation\DirConfig;

use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;

use Skeleton\Skeleton;
use Skeleton\ImplementersMap;
use Skeleton\ConfigLoader\DirectoryConfigLoader;


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
	
	
	/** @var ImplementersMap\TestMap|null */
	private static $testMap = null;
	
	/** @var Skeleton */
	private static $skeleton;
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
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
	
	
	/**
	 * @param string|null $interface Set to null to get the skeleton object.
	 * @return mixed
	 */
	public static function skeleton($interface = null)
	{
		if (is_null(self::$skeleton))
			self::$skeleton = (new Skeleton())
				->setMap(new ImplementersMap\LazyLoadMap())
				->setConfigLoader(new DirectoryConfigLoader(__DIR__ . '/_skeleton'));
		
		if (is_null($interface)) 
			return self::$skeleton;
			
		return self::$skeleton->get($interface);
	}
	
	/**
	 * @param string $interface
	 * @param mixed $definition
	 */
	public static function override($interface, $definition) 
	{
		if (is_null(self::$testMap)) 
		{
			self::$testMap = new ImplementersMap\TestMap(self::skeleton()->getMap());
			self::skeleton()->setMap(self::$testMap);
		}
		
		self::$testMap->override($interface, $definition);
	}
	
	public static function reset() 
	{
		self::$testMap->reset();
	}
}