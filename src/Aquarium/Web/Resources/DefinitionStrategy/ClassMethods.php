<?php
namespace Aquarium\Web\Resources\DefinitionStrategy;


use Aquarium\Web\Resources\Package;
use Aquarium\Web\Resources\Utils\Builder;
use Aquarium\Web\Resources\Utils\PackageUtils;
use Aquarium\Web\Resources\Package\IPackageDefinitionManager;


class ClassMethods implements IPackageDefinitionManager
{
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
		$path = explode(PackageUtils::PACKAGE_PATH_SEPARATOR, $name);
		return strtolower(implode('_', $path));
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
}