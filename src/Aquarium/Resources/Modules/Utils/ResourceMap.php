<?php
namespace Aquarium\Resources\Modules\Utils;


class ResourceMap
{
	/**
	 * @var array Where key is new resource location and value is the resource 
	 * (or resources) that will be transformed. 
	 */
	private $map = [];
	
	
	/**
	 * @param string|array $from
	 * @param string $to
	 * @return static
	 */
	public function map($from, $to) 
	{
		if (is_array($to) && count($to) === 1) 
			$to = array_shift($to);
		
		$this->map[$to] = $from;
		return $this;
	}
	
	
	/**
	 * @param ResourceCollection $collection
	 */
	public function apply(ResourceCollection $collection)
	{
		foreach ($this->map as $newFile => $oldFile)
		{
			if (is_string($oldFile)) 
			{
				$collection->replace($oldFile, $newFile);
				continue;
			}
			
			$firstResource = array_shift($oldFile);
			$collection->replace($firstResource, $newFile);
			$collection->remove($oldFile);
		}
	}
	
	/**
	 * @param ResourceMap $map
	 */
	public function modify(ResourceMap $map) 
	{
		$newMap = $map->getMap();
		
		// Collection of maps that does not exist in the new map.
		$notCloned = $this->map;
		
		
		foreach ($map->getMap() as $newResource => $oldResource) 
		{
			foreach ($this->map as $originalNewResource => $initialResource)
			{
				if (is_string($oldResource)) 
				{
					if ($originalNewResource == $oldResource) 
					{
						$newMap[$newResource] = $initialResource;
						unset($notCloned[$originalNewResource]);
					}
				}
				else 
				{
					$position = array_search($originalNewResource, $newMap[$newResource]);
					
					if ($position !== false)
					{
						array_splice($newMap[$newResource], $position, 1, $initialResource);
						unset($notCloned[$originalNewResource]);
					}
				}
			}
		}
		
		$this->map = array_merge(
			$newMap,
			$notCloned
		);
	}
	
	/**
	 * @return array
	 */
	public function getMap() 
	{
		return $this->map;
	}
}