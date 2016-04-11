<?php
namespace Aquarium\Resources\Modules\Utils;


class FileSystem
{
	
	/**
	 * @param string $a
	 * @param string $b
	 * @return bool
	 */
	private function isBinaryFilesSame($a, $b) 
	{
		$aHandle = fopen($a, 'rb');
		$bHandle = fopen($b, 'rb');
		
		if ($aHandle === false) 
			throw new \Exception("Failed to open file $a");
		else if ($b === false) 
			throw new \Exception("Failed to open file $b");
		
		try 
		{
			while (true)
			{
				$aData = fread($aHandle, 1024);
				$bData = fread($bHandle, 1024);
				
				if ($aData != $bData || feof($aHandle) != feof($bHandle))
					return false;
				
				if (feof($aHandle)) 
					break;
			}
		} 
		finally 
		{
			fclose($aHandle);
			fclose($bHandle);
		}
		
		return true;
	}
	
	
	/**
	 * Delete all files in a directory that have the given prefix.
	 * @param string $dir
	 * @param string $prefix
	 * @return int Number of deleted files.
	 */
	public function deleteFilesByPrefix($dir, $prefix) 
	{
		$deleted = 0;
		
		if (!$prefix || strpos($prefix, '*') !== false) 
			throw new \Exception("Invalid prefix [$prefix]");
		
		foreach (glob("$dir/$prefix") as $item) 
		{
			if (is_file($item)) continue;
			
			$deleted++;
			
			if (!unlink($item)) 
				throw new \Exception("Failed to delete file [$item]");
		}
		
		return $deleted;
	}
	
	/**
	 * @param string $a
	 * @param string $b
	 * @return bool
	 */
	public function isFilesSame($a, $b)
	{
		if (!is_file($a) || !is_file($b)) return false;
		
		$aSize = filesize($a);
		$bSize = filesize($b);
		
		if ($a === false) 
			throw new \Exception("Failed to get size for file $a");
		else if ($b === false) 
			throw new \Exception("Failed to get size for file $b");
		
		if ($aSize != $bSize) return false;
		
		return $this->isBinaryFilesSame($a, $b);
	}
}