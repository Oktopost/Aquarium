<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;


class Utils
{
	use \Objection\TStaticClass;
	
	
	const PACKAGE_CLASS_NAME_PREFIX = 'CompiledPackage_';
	const PACKAGE_PATH_SEPARATOR	= '_';
	const FILE_NAME_PREFIX			= 'CompiledFile_';
	
	const COMPILED_CLASSES_NAMESPACE = 'Aquarium\Resources\CompiledScripts';
	
	
	/**
	 * @param Package|string $packageName
	 * @return string
	 */
	public static function getClassName($packageName) 
	{
		if ($packageName instanceof Package) 
			return 
				self::PACKAGE_CLASS_NAME_PREFIX . 
				$packageName->getName(self::PACKAGE_PATH_SEPARATOR);
		
		return 
			self::PACKAGE_CLASS_NAME_PREFIX .
			str_replace(Package::PACKAGE_PATH_SEPARATOR, self::PACKAGE_PATH_SEPARATOR, $packageName);
	}
	
	/**
	 * @param Package|string $packageName
	 * @return string
	 */
	public static function getFullClassName($packageName) 
	{
		return self::COMPILED_CLASSES_NAMESPACE . '\\' . Utils::getClassName($packageName);
	}
	
	/**
	 * @param \Aquarium\Resources\Package|string $packageName
	 * @return string
	 */
	public static function getClassPath($packageName)
	{
		if ($packageName instanceof Package) 
			return self::getClassPath($packageName->Name);
		
		return Config::instance()->Directories->PhpTargetDir . 
			DIRECTORY_SEPARATOR . self::getClassName($packageName) . '.php';
	}
}