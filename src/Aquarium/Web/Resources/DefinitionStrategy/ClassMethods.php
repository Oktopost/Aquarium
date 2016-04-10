<?php
namespace Aquarium\Web\Resources\DefinitionStrategy;


use Aquarium\Web\Resources\Compilers\Gulp\GulpCommand;
use Aquarium\Web\Resources\Config;
use Aquarium\Web\Resources\Package;
use Aquarium\Web\Resources\Utils\Builder;
use Aquarium\Web\Resources\Utils\PackageUtils;
use Aquarium\Web\Resources\Package\IPackageDefinitionManager;


class ClassMethods implements IPackageDefinitionManager
{
	const PACKAGE_METHOD_PREFIX	= 'Package_';
	
	
	private $configClassName;
	private $configObject = null;
	
	/** @var Package[] By package name */
	private $cached = [];
	
	
	/**
	 * @return null
	 */
	private function getObject()
	{
		if (is_null($this->configObject))
			$this->configObject = new $this->configClassName;
		
		return $this->configObject;
	}
	
	/**
	 * @param string $name
	 * @return string
	 */
	private function getFunctionName($name)
	{
		$path = explode(Package::PACKAGE_PATH_SEPARATOR, $name);
		return self::PACKAGE_METHOD_PREFIX . implode('_', $path);
	}
	
	
	/**
	 * @param string $configClassName
	 */
	public function __construct($configClassName)
	{
		$this->configClassName = $configClassName;
	}
	
	
	/**
	 * @param string $name
	 * @return Package
	 */
	public function get($name)
	{
		if (!isset($this->cached[$name]))
		{
			$builder	= new Builder();
			$package	= new Package($name);
			$object		= $this->getObject();
			$funcName	= $this->getFunctionName($name);
			
			$builder->setup($package);
			call_user_func([$object, $funcName], $builder);
			
			$this->cached[$name] = $package;
		}
		
		return $this->cached[$name];
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name)
	{
		if (isset($this->cached[$name])) return true;
		
		$object		= $this->getObject();
		$funcName	= $this->getFunctionName($name);
		
		return method_exists($object, $funcName);
	}
	
	
	/**
	 * @return array Array of all existing package definitions
	 */
	public function getNames()
	{
		$object			= $this->getObject();
		$reflection		= new \ReflectionClass($object);
		$methods		= $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
		$packageNames	= [];
		
		foreach ($methods as $method)
		{
			if (strpos($method->name, self::PACKAGE_METHOD_PREFIX) === 0)
			{
				$packageNames[] = $method->name;
			}
		}
		
		return $packageNames;
	}
}