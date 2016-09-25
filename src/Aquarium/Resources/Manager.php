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
		$consumer = Config::instance()->consumer();
		
		Config::instance()->directories()->truncateResourcesToPublicDir($package);
		
		$this->dependencies[$package->Name] = true;
		
		foreach ($package->Requires as $required)
		{
			$this->package($required);
		}
		
		foreach ($package->Inscribed as $inscribed)
		{
			$this->package($inscribed);
		}
		
		foreach ($package->Styles as $style)
		{
			$consumer->addStyle($style);
		}
		
		foreach ($package->Scripts as $script)
		{
			$consumer->addScript($script);
		}
		
		foreach ($package->Views as $script)
		{
			$consumer->addScript($script);
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
		
		$package = Config::instance()->packageDefinitionManager()->get($name);
		
		if (Config::instance()->compiler())
		{
			$package = Config::instance()->compiler()->compilePackage($package);
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
		Config::instance()->consumer()->addScript($path);
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function style($path)
	{
		Config::instance()->consumer()->addStyle($path);
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function view($path)
	{
		Config::instance()->consumer()->addView($path);
		return $this;
	}
}