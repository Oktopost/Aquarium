<?php
namespace Aquarium\Resources\Modules\DefinitionStrategy;


use Aquarium\Resources\Package;
use Aquarium\Resources\Modules\Compilation\Utils;
use Aquarium\Resources\Modules\Package\IPackageDefinitionManager;
use Aquarium\Resources\Modules\Utils\Builder;


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
			$namespace = Utils::getClassName($name);
			
			$package = new Package($name);
			$builder = new Builder();
			
			$this->cached[$name] = $package;
			$builder->setup($package);
			
			if (!is_file($name) || !is_readable($name))
				throw new \Exception("Can't load file '$path'");
			
			/** @noinspection PhpIncludeInspection */
			require_once $path;
			
			/** @var \Aquarium\Resources\Modules\CompiledScripts\CompiledPackage_PackageName $namespace */
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