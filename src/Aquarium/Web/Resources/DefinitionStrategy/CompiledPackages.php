<?php
namespace Aquarium\Web\Resources\DefinitionStrategy;


use Aquarium\Web\Resources\Compilation\Utils;
use Aquarium\Web\Resources\Package;
use Aquarium\Web\Resources\Package\IPackageDefinitionManager;
use Aquarium\Web\Resources\Utils\Builder;


class CompiledPackages implements IPackageDefinitionManager
{
	/** @var Package[] By package name */
	private $cached = [];
	
	
	/**
	 * @param string $packageName
	 * @return string
	 */
	private function getPathToPackage($packageName) 
	{
		Utils::getClassName($packageName);
	}
	
	
	/**
	 * @param string $name
	 * @return Package
	 */
	public function get($name)
	{
		if (!isset($this->cached[$name]))
		{
			$path = Utils::getClassPath($name);
			$namespace = Utils::getClassName($name);
			
			$package = new Package($name);
			$builder = new Builder();
			
			$this->cached[$name] = $package;
			$builder->setup($package);
			
			if (!is_file($name) || !is_readable($name))
				throw new \Exception("Can't load file '$path'");
			
			/** @noinspection PhpIncludeInspection */
			require_once $path;
			
			/** @var \Aquarium\Web\Resources\CompiledScripts\CompiledPackage_PackageName $namespace */
			$namespace::get($builder); 
		}
		
		return $this->cached[$name];
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name)
	{
		return isset($this->cached[$name]) || is_file(Utils::getClassPath($name)); 
	}
	
	/**
	 * @return array Array of all existing package definitions
	 */
	public function getNames()
	{
		throw new \Exception('This class should not be used for compilation');
	}
}