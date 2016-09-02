<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Logger\CmdLogger;
use Aquarium\Resources\Package\IPackageDefinitionManager;
use Aquarium\Resources\Compilation\DirConfig;

use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;

use Skeleton\Skeleton;
use Skeleton\UnitTestSkeleton;
use Skeleton\ConfigLoader\DirectoryConfigLoader;

use Psr\Log\LoggerInterface;


/**
 * @property IConsumer 					$Consumer
 * @property IProvider					$Provider
 * @property IPackageDefinitionManager 	$DefinitionManager
 * @property DirConfig 					$Directories
 * @property ICompiler 					$GulpCompiler
 * @property LoggerInterface 			$Log
 */
class Config extends LiteObject
{
	use \Objection\TSingleton;
	
	
	/** @var UnitTestSkeleton */
	private static $testMap = null;
	
	/** @var Skeleton */
	private static $skeleton;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->Log = new CmdLogger();
	}
	
	
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Consumer'			=> LiteSetup::createInstanceOf(IConsumer::class), 
			'Provider'			=> LiteSetup::createInstanceOf(IProvider::class), 
			'DefinitionManager'	=> LiteSetup::createInstanceOf(IPackageDefinitionManager::class),
			'Directories'		=> LiteSetup::createInstanceOf(new DirConfig(), AccessRestriction::NO_SET),
			'GulpCompiler'			=> LiteSetup::createInstanceOf(ICompiler::class),
			'Log'				=> LiteSetup::createInstanceOf(LoggerInterface::class),
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
				->enableKnot()
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
			self::$testMap = new UnitTestSkeleton(self::skeleton());
			self::skeleton()->setMap(self::$testMap);
		}
		
		self::$testMap->override($interface, $definition);
	}
	
	public static function reset() 
	{
		self::$testMap->clear();
	}
}