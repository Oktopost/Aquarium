<?php
namespace Aquarium\Resources\Utils;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Package\IPackageBuilder;


class PackageBuilder implements IPackageBuilder
{
	/** @var Package */
	private $package = null;
	
	private $loadDirectory = false;
	
	
	/** @var bool */
	private static $isTestMode = false;
	
	
	/**
	 * @param bool$isTestMode
	 */
	public static function setTestMode($isTestMode = false)
	{
		self::$isTestMode = $isTestMode;
	}
	
	
	private function addAllFilesInDirectory($resource)
	{
		$result = [];
		
		$splitted = explode('*', $resource);
		
		if (isset($splitted[2]))
		{
			throw new \Exception('Can not use more than one asterisk in path');
		}
		
		$dir = $splitted[0];
		$prefix = $this->setPrefix($dir);
		$suffix = $this->setSuffix($splitted[1]);
		$path = Config::instance()->directories()->getPathToSource($dir);
		$scan = scandir($path);
		
		foreach ($scan as $item)
		{
			$endPoint = $path . DIRECTORY_SEPARATOR . $item;
			if (
				$item != '.' &&
				$item != '..' &&
				!is_dir($endPoint) &&
				preg_match($prefix, $item) &&
				preg_match($suffix, $item)
			)
			{
				$result[] = $endPoint;
				
			}
		}
		
		return $result;
	}
	
	/**
	 * @param string $resource
	 * @return bool|int|string
	 */
	private function getFullPath($resource)
	{
		if (self::$isTestMode || $resource[0] == DIRECTORY_SEPARATOR)
			return $resource;
		
		if (preg_match('/(\*)/', $resource))
		{
			$this->loadDirectory = true;
			$fullPath = $this->addAllFilesInDirectory($resource);
		}
		else
		{
			$this->loadDirectory = false;
			$fullPath = Config::instance()->directories()->getPathToSource($resource);
		}
		
		if (!$fullPath)
			throw new \Exception("Can't find path to source file '$resource'");
		
		return $fullPath;
	}
	
	
	/**
	 * @param $dir
	 * @return string
	 */
	private function setPrefix(&$dir)
	{
		if ($dir[strlen($dir) - 1] != DIRECTORY_SEPARATOR)
		{
			$pathAsArray = explode(DIRECTORY_SEPARATOR, $dir);
			$prefix = '/^' . array_pop($pathAsArray) . '/';
			$dir = implode(DIRECTORY_SEPARATOR, $pathAsArray);
			
			return $prefix;
		}
		
		return '//';
	}
	
	/**
	 * @param $string
	 * @return string
	 */
	private function setSuffix($string)
	{
		if ($string == null)
		{
			return '//';
		}
		
		return '/' . $string . '$/';
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
		$this->package->Styles->add($this->getFullPath($style), $this->loadDirectory);
		return $this;
	}
	
	/**
	 * @param string $script
	 * @return static
	 */
	public function script($script)
	{
		$this->package->Scripts->add($this->getFullPath($script), $this->loadDirectory);
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
	
	/**
	 * @param string $package
	 * @return static
	 */
	public function inscribe($package)
	{
		$this->package->Inscribed->add($package);
		return $this;
	}
}