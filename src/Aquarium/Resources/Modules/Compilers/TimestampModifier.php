<?php
namespace Aquarium\Resources\Modules\Compilers;


use Aquarium\Resources\Modules\Utils\ResourceCollection;


class TimestampModifier
{
	/**
	 * @param bool $time
	 * @return string
	 */
	private function getTimestamp($time = false)
	{
		$time = ($time ?: time());
		
		return base_convert((string)$time, 10, 36);
	}
	
	/**
	 * @param string $fileName
	 * @param string $element
	 * @return string
	 */
	private function createResourceStub($fileName, $element)
	{
		$lastDot = strrpos($fileName, '.');
		
		if ($lastDot === false)
			return "$fileName.$element";
		
		return
			substr($fileName, 0, $lastDot - 1) .
			".$element." .
			substr($fileName, $lastDot + 1);
	}
	
	/**
	 * @param string $fileName
	 * @param string $time
	 * @throws \Exception
	 */
	private function rename($fileName, $time)
	{
		$newFileName = $this->createResourceStub($fileName, $time);
		
		if (!rename($fileName, $newFileName))
			throw new \Exception("Failed to rename file $fileName to $newFileName");
	}
	
	/**
	 * @param string $resourcePath
	 * @return int
	 */
	private function getResourceTimestamp($resourcePath)
	{
		$stub = $this->createResourceStub($resourcePath, '*');
		
		// Check if file exists.
		
		// If not return 0
		// Else extract
		$d = 'abc';
		
		return (int)base_convert($d, 36, 10);
	}
	
	
	/**
	 * @param ResourceCollection $collection
	 * @return array Key is resource path and value is it's last modified date.
	 */
	public function getResourcesTimestamp(ResourceCollection $collection)
	{
		$result = [];
		
		foreach ($collection as $resource)
		{
			$result[$resource] = $this->getResourceTimestamp($resource);
		}
	}
}