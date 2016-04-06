<?php
namespace Aquarium\Web\Resources\Utils;


class PackageUtils 
{
	use \Objection\TStaticClass;
	
	
	const PACKAGE_PATH_SEPARATOR = '/';
	
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function isValidPackageName($name) 
	{
		$allowed = 'a-z0-9\-\_';
		$separator = self::PACKAGE_PATH_SEPARATOR;
		
		return (bool)preg_match("/^[$allowed]+(\\{$separator}[$allowed]+)*$/i", $name);
	}
}