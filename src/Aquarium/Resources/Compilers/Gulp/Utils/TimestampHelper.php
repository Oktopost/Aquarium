<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


class TimestampHelper
{
	use \Objection\TStaticClass;
	
	
	const TIMESTAMP_PREFIX		= 'T-';
	
	
	/**
	 * @param string $source
	 * @return int|bool
	 */
	public static function getTimestampFromFileName($source)
	{
		$matches = [];
		
		$dirSep = DIRECTORY_SEPARATOR;
		$prefix = self::TIMESTAMP_PREFIX;
		$findTimestampRegex = "/^.*\\$dirSep.*\\.$prefix([0-9a-z]{6})(|.css|.js)$/";
		
		if (!preg_match($findTimestampRegex, $source, $matches))
			return false;
		
		return (int)base_convert($matches[1], 36, 10);
	}
	
	/**
	 * Find the target file with timestamp data attached to it.
	 * @param string $source
	 * @return string|bool Return false string if file does not exists.
	 */
	public static function findFileWithTimestamp($source)
	{
		$prefix = self::TIMESTAMP_PREFIX;
		$findTimestampRegex = "/^((.*)(.css|.js)|(.*)())$/";
		
		if (!preg_match($findTimestampRegex, $source, $matches))
			return false;
		
		if (isset($matches[4]) && $matches[4])
		{
			$fileSearchPattern = "{$matches[4]}.$prefix*";
		}
		else 
		{
			$fileSearchPattern = "{$matches[2]}.$prefix*{$matches[3]}";
		}
		
		
		/**
		 * Glob sorts results alphabetically
		 * @link http://php.net/manual/en/function.glob.php
		 */ 
		$allFiles = glob($fileSearchPattern);
		
		return ($allFiles ? end($allFiles) : false);
	}
	
	/**
	 * @param int|bool $time
	 * @return string
	 */
	public static function generateTimestamp($time = false)
	{
		return self::TIMESTAMP_PREFIX . base_convert((string)($time ?: time()), 10, 36);
	}
	
	/**
	 * @param string $file
	 * @param int|bool $time
	 * @return string|bool New file name with timestamp 
	 */
	public static function generateTimestampForFile($file, $time = false)
	{
		$timestamp = self::generateTimestamp($time);
		$findTimestampRegex = "/^((.*)(.css|.js)|(.*))$/";
		
		if (!preg_match($findTimestampRegex, $file, $matches))
			return false;
		
		if (isset($matches[4]) && $matches[4])
			return "{$matches[4]}.$timestamp";
			
		return "{$matches[2]}.$timestamp{$matches[3]}"; 
	}
}