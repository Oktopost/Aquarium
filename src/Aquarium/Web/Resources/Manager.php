<?php
namespace Aquarium\Web\Resources;


class Manager implements IProvider 
{
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name) 
	{
		Config::instance()->Provider->package($name);
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