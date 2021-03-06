<?php
namespace Aquarium\Resources\DefinitionStrategy;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\PackageBuilder;
use Aquarium\Resources\Package\IPackageDefinitionManager;
use Aquarium\Resources\Compilation\Utils;


class CompiledPackages implements IPackageDefinitionManager
{
	/** @var \Aquarium\Resources\Package[] By package name */
	private $cached = [];
	
	
	/**
	 * @param string $name
	 * @return Package
	 */
	public function get($name)
	{
		if (!isset($this->cached[$name]))
		{
			$path = Utils::getClassPath($name);
			$fullClassName = Utils::getFullClassName($name);
			
			$package = new Package($name);
			$builder = new PackageBuilder();
			
			$this->cached[$name] = $package;
			$builder->setup($package);
			
			if (!is_file($path) || !is_readable($path))
				throw new \Exception("Can't load file '$path'");
			
			/** @noinspection PhpIncludeInspection */
			require_once $path;
			
			/** @noinspection PhpUndefinedMethodInspection */
			$fullClassName::get($builder); 
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