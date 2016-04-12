<?php
namespace Aquarium\Resources\Modules\Utils;


class ResourceCollection implements \Iterator
{
	private $collection = [];
	
	
	/**
	 * @param array $collection
	 */
	public function __construct($collection = [])
	{
		$this->collection = $collection;
	}
	
	
	/**
	 * @param array|string $resource
	 * @return static
	 */
	public function add($resource)
	{
		if (is_array($resource))
		{
			$this->collection = array_merge($this->collection, array_diff($resource, $this->collection));
		}
		else if (!in_array($resource, $this->collection))
		{
			$this->collection[] = $resource;
		}
		
		return $this;
	}
	
	/**
	 * @param string $resource
	 */
	public function hasResource($resource) 
	{
		return in_array($resource, $this->collection);
	}
	
	/**
	 * @return bool
	 */
	public function hasAny() 
	{
		return (bool)$this->collection;
	}
	
	/**
	 * @param string $from
	 * @param string $to
	 * @return static
	 */
	public function replace($from, $to) 
	{
		$index = array_search($from, $this->collection);
		
		if ($index === false) return $this;
		
		array_splice($this->collection, $index, 1, $to);
		
		return $this;
	}
	
	/**
	 * @param array|string $target
	 * @return static
	 */
	public function remove($target) 
	{
		if (!is_array($target)) $target = [$target];
		
		foreach ($target as $oldResource) 
		{
			$index = array_search($oldResource, $this->collection);
			
			if ($index !== false) 
				array_splice($this->collection, $index, 1);
		}
		
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function count() 
	{
		return count($this->collection);
	}
	
	/**
	 * @param string|bool $filter
	 * @return array
	 */
	public function get($filter = false) 
	{
		if (!$filter) return $this->collection;
		
		return array_values(array_filter($this->collection, 
			function($value) 
				use ($filter) 
			{
				return fnmatch($filter, $value); 
			}));
	}
	
	/**
	 * @return string
	 */
	public function current()
	{
		return current($this->collection);
	}
	
	/**
	 * @return string|bool
	 */
	public function next()
	{
		return next($this->collection);
	}
	
	/**
	 * @return int
	 */
	public function key()
	{
		return key($this->collection);
	}
	
	/**
	 * @return bool
	 */
	public function valid()
	{
		return is_int(key($this->collection));
	}
	
	public function rewind()
	{
		reset($this->collection);
	}
}