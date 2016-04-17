<?php
namespace Aquarium\FileSystem;


use Objection\TStaticClass;


class Utils
{
	use TStaticClass; 
	
	
	public static function validatePath($path)
	{
		$windowsSeparator = strpos($path, WINDOWS_DIRECTORY_SEPARATOR);
		$linuxSeparator = strpos($path, LINUX_DIRECTORY_SEPARATOR);
		
		if ($windowsSeparator !== false && DIRECTORY_SEPARATOR == LINUX_DIRECTORY_SEPARATOR)
		{
			return str_replace(WINDOWS_DIRECTORY_SEPARATOR, LINUX_DIRECTORY_SEPARATOR, $path);
		}
		else if ($linuxSeparator !== false && DIRECTORY_SEPARATOR == WINDOWS_DIRECTORY_SEPARATOR)
		{
			return str_replace(LINUX_DIRECTORY_SEPARATOR, WINDOWS_DIRECTORY_SEPARATOR, $path);
		}
		
		return $path;
	}
	
	
	public static function pathAsArray($path)
	{
		$windowsSeparator = strpos($path, WINDOWS_DIRECTORY_SEPARATOR);
		$linuxSeparator = strpos($path, LINUX_DIRECTORY_SEPARATOR);
		
		if ($windowsSeparator !== false && $linuxSeparator !== false)
			$path = str_replace(WINDOWS_DIRECTORY_SEPARATOR, LINUX_DIRECTORY_SEPARATOR, $path);
		
		if ($linuxSeparator)
			return explode(LINUX_DIRECTORY_SEPARATOR, $path);
		
		return explode(WINDOWS_DIRECTORY_SEPARATOR, $path);
	}
}