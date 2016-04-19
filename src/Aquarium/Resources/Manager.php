<?php
namespace Aquarium\Resources;


class Manager implements IProvider 
{
	/** @var array */
	private $dependencies = [];
	
	
	/**
	 * @param Package $package
	 */
	private function appendPackage(Package $package)
	{
		Config::instance()->Directories->truncateResourcesToPublicDir($package);
		
		$this->dependencies[$package->Name] = true;
		
		foreach ($package->Requires as $required)
		{
			$this->package($required);
		}
		
		foreach ($package->Styles as $style)
		{
			Config::instance()->Consumer->addStyle($style);
		}
		
		foreach ($package->Scripts as $script)
		{
			Config::instance()->Consumer->addScript($script);
		}
	}
	
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name) 
	{
		if (isset($this->dependencies[$name]))
			return $this;
		
		if (!Package::isValidPackageName($name)) 
			throw new \Exception('Package name is invalid');
		
		$package = Config::instance()->DefinitionManager->get($name);
		
		if (Config::instance()->Compiler)
		{
			$package = Config::instance()->Compiler->compilePackage($package);
		}
		
		$this->appendPackage($package);
		
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function script($path) 
	{
		Config::instance()->Consumer->addScript($path);
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function style($path)
	{
		Config::instance()->Consumer->addStyle($path);
		return $this;
	}
}