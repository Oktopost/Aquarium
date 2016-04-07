<?php
namespace Aquarium\Web\Resources;


class Manager implements IProvider 
{
	/** @var array */
	private $dependencies = [];
	
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name) 
	{
		if (isset($this->dependencies[$name]))
			return $this;
		
		if (!Utils\PackageUtils::isValidPackageName($name)) 
			throw new \Exception('Package name is invalid');
		
		$this->dependencies[$name] = true;
		Config::instance()->PackageConstructor->append($name);
		
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