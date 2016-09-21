<?php
namespace Aquarium\Resources\DefinitionStrategy;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\PackageBuilder;
use Aquarium\Resources\Package\IPackageDefinitionManager;


class ClassMethods implements IPackageDefinitionManager
{
	const PACKAGE_METHOD_PREFIX	= 'Package_';
	const PACKAGE_PATH_SEPARATOR = '_';
	
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
		return self::PACKAGE_METHOD_PREFIX . implode(self::PACKAGE_PATH_SEPARATOR, $path);
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
			$builder	= new PackageBuilder();
			$package	= new Package($name);
			$object		= $this->getObject();
			$funcName	= $this->getFunctionName($name);
			
			if (!method_exists($object, $funcName)) 
				throw new \Exception("Package '$name' is not defined");
			
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
				$name = substr($method->name, strlen(self::PACKAGE_METHOD_PREFIX));
				$name = str_replace(self::PACKAGE_PATH_SEPARATOR, Package::PACKAGE_PATH_SEPARATOR, $name);
				
				$packageNames[] = $name;
			}
		}
		
		return $packageNames;
	}
}