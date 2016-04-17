<?php
namespace Aquarium\Resources\Utils;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Package\IBuilder;


class Builder implements IBuilder
{
	/** @var Package */
	private $package = null;
	
	/** @var bool */
	private static $isTestMode = false;
	
	
	/**
	 * @param bool$isTestMode
	 */
	public static function setTestMode($isTestMode = false)
	{
		self::$isTestMode = $isTestMode;
	}
	
	
	/**
	 * @param string $resource
	 * @return string|int|bool
	 */
	private function getFullPath($resource)
	{
		if (self::$isTestMode || $resource[0] == DIRECTORY_SEPARATOR)
			return $resource;
		
		$fullPath = Config::instance()->Directories->getPathToSource($resource);
		
		if (!$fullPath)
			throw new \Exception("Can't find path to source file '$resource'");
		
		return $fullPath;
	}
	
	
	/**
	 * @param Package $package
	 * @return static
	 */
	public function setup(Package $package) 
	{
		$this->package = $package;
		return $this;
	}
	
	/**
	 * @param string $style
	 * @return static
	 */
	public function style($style)
	{
		$this->package->Styles->add($this->getFullPath($style));
		return $this;
	}
	
	/**
	 * @param string $script
	 * @return static
	 */
	public function script($script)
	{
		$this->package->Scripts->add($this->getFullPath($script));
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name)
	{
		$this->package->Requires->add($name);
		return $this;
	}
}