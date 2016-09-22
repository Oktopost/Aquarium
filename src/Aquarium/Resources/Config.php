<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Base\Log\ILogHandler;
use Aquarium\Resources\Base\Config\ILogConfig;
use Aquarium\Resources\Package\IPackageDefinitionManager;
use Aquarium\Resources\Compilers\GulpPackageCompiler;
use Aquarium\Resources\Compilation\DirConfig;

use Skeleton\Skeleton;
use Skeleton\UnitTestSkeleton;
use Skeleton\ConfigLoader\DirectoryConfigLoader;


class Config
{
	use \Objection\TSingleton;
	
	
	/** @var ILogConfig */
	private $logConfig;
	
	/** @var DirConfig */
	private $directories;
	
	/** @var IConsumer */
	private $consumer = null;
	
	/** @var IProvider */
	private $provider = null;
	
	/** @var IPackageDefinitionManager */
	private $packageDefinitionManager = null;
	
	/** @var ICompiler */
	private $compiler = null;
	
	
	/** @var UnitTestSkeleton */
	private static $testMap = null;
	
	/** @var Skeleton */
	private static $skeleton;
	
	
	/**
	 * @param static $instance
	 */
	protected static function initialize($instance) 
	{
		$instance->directories = new DirConfig();
		$instance->logConfig = self::skeleton(ILogConfig::class);
	}
	
	
	/**
	 * @return ILogConfig
	 */
	public function logConfig()
	{
		return $this->logConfig;
	}
	
	/**
	 * @return DirConfig
	 */
	public function directories()
	{
		return $this->directories;
	}
	
	/**
	 * @return IConsumer 
	 */
	public function consumer()
	{
		if (!$this->consumer)
			throw new \Exception('Consumer was not defined');
			
		return $this->consumer;
	}
	
	/**
	 * @return IProvider
	 */
	public function provider()
	{
		if (!$this->provider)
			throw new \Exception('Provider was not defined');
		
		return $this->provider;
	}
	
	/**
	 * @return IPackageDefinitionManager
	 */
	public function packageDefinitionManager()
	{
		if (!$this->packageDefinitionManager)
			throw new \Exception('PackageDefinitionManager was not defined');
		
		return $this->packageDefinitionManager;
	}
	
	/**
	 * @return ICompiler
	 */
	public function compiler()
	{
		if (!$this->compiler)
			$this->compiler = new GulpPackageCompiler();
		
		return $this->compiler;
	}
	
	
	/**
	 * @param IConsumer $consumer
	 * @return static
	 */
	public function setConsumer(IConsumer $consumer)
	{
		$this->consumer = $consumer;
		return $this;
	}
	
	/**
	 * @param IProvider $provider
	 * @return static
	 */
	public function setProvider(IProvider $provider)
	{
		$this->provider = $provider;
		return $this;
	}
	
	/**
	 * @param IPackageDefinitionManager $manager
	 * @return static
	 */
	public function setPackageDefinitionManager(IPackageDefinitionManager $manager)
	{
		$this->packageDefinitionManager = $manager;
		return $this;
	}
	
	/**
	 * @param ICompiler $compiler
	 * @return static
	 */
	public function setCompiler(ICompiler $compiler)
	{
		$this->compiler = $compiler;
		return $this;
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
	
	/**
	 * @return ILogHandler
	 */
	public static function log()
	{
		/** @var ILogConfig $logConfig */
		$logConfig = self::skeleton(ILogConfig::class);
		return $logConfig->log();
	}
	
	public static function reset() 
	{
		self::$testMap->clear();
	}
}