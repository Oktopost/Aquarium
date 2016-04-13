<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;


class Utils
{
	use \Objection\TStaticClass;
	
	
	const PACKAGE_CLASS_NAME_PREFIX = 'CompiledPackage_';
	const FILE_NAME_PREFIX			= 'CompiledFile_';
	
	const COMPILED_CLASSES_NAMESPACE = 'Aquarium\Resources\CompiledScripts';
	
	
	/**
	 * @param Package|string $packageName
	 * @return string
	 */
	public static function getClassName($packageName) 
	{
		if ($packageName instanceof Package) 
			return self::getClassName($packageName->Name);
		
		return 
			self::PACKAGE_CLASS_NAME_PREFIX .
			implode('_', explode(Package::PACKAGE_PATH_SEPARATOR, $packageName));
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
	
	/**
	 * @param string $fileName Current file name.
	 * @param int|bool $time Unix timestamp.
	 * @return string New name base on last modified date. 
	 */
	public static function resourceNameGenerator($fileName, $time = false)
	{
		$timeMask = base_convert((string)($time ?: time()), 10, 36);
		$fileNameParts = explode('.', $fileName);
		
		array_splice($fileNameParts, count($fileNameParts) - 1, 0, $timeMask);
		
		return implode('.', $fileNameParts);
	}
}