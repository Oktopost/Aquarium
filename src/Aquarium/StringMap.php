<?php
namespace Aquarium;


/**
 * 1 to 1 mapping. Each source can have only one target and each target can have only one source.
 */
class StringMap implements \Iterator
{
	private $targetToSource = [];
	private $sourceToTarget = [];
	
	/**
	 * @param array|bool $args
	 */
	public function __construct($args = false)
	{
		if (!$args) return;
		
		$this->set($args);
	}
	
	
	/**
	 * Unlike set, throws exception if source or target already defined.
	 * @param string|array $source
	 * @param string|bool $target
	 */
	public function add($source, $target = false)
	{
		if (is_array($source))
		{
			foreach ($source as $s => $t)
			{
				$this->add($s, $t);
			}
			
			return;
		}
		
		if (isset($this->sourceToTarget[$source]))
			throw new \Exception("Can not map '$source' to '$target'. " .  
				"Source '$source' already defined for {$this->sourceToTarget[$source]}");
		
		if (isset($this->targetToSource[$target]))
			throw new \Exception("Can not map '$source' to '$target'. " . 
				"Target '$target' already defined for {$this->targetToSource[$target]}");
		
		$this->set($source, $target);
	}
	
	/**
	 * @param string|array $source
	 * @param string|bool $target
	 */
	public function set($source, $target = false)
	{
		if (is_array($source))
		{
			$this->sourceToTarget = array_merge($this->sourceToTarget, $source);
			$this->targetToSource = array_merge($this->targetToSource, array_flip($source));
		} 
		else 
		{
			$this->targetToSource[$target] = $source;
			$this->sourceToTarget[$source] = $target;
		}
	}
	
	/**
	 * @param string $target
	 * @return string|bool
	 */
	public function getSource($target) 
	{
		return isset($this->targetToSource[$target]) ? $this->targetToSource[$target] : false; 
	}
	
	/**
	 * @param string $source
	 * @return string|bool
	 */
	public function getTarget($source)
	{
		return isset($this->sourceToTarget[$source]) ? $this->sourceToTarget[$source] : false; 
	}
	
	/**
	 * @param string $target
	 * @return bool
	 */
	public function hasTarget($target)
	{
		return isset($this->targetToSource[$target]);
	}
	
	/**
	 * @param string $source
	 * @return bool
	 */
	public function hasSource($source)
	{
		return isset($this->sourceToTarget[$source]);
	}
	
	/**
	 * @return int
	 */
	public function count() 
	{
		return count($this->sourceToTarget);
	}
	
	/**
	 * @param string|bool $filter
	 * @return array
	 */
	public function get($filter = false) 
	{
		if (!$filter) return $this->sourceToTarget;
		
		return array_values(array_filter($this->sourceToTarget, 
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
		return current($this->sourceToTarget);
	}
	
	/**
	 * @return string|bool
	 */
	public function next()
	{
		return next($this->sourceToTarget);
	}
	
	/**
	 * @return int
	 */
	public function key()
	{
		return key($this->sourceToTarget);
	}
	
	/**
	 * @return bool
	 */
	public function valid()
	{
		return is_int(key($this->sourceToTarget));
	}
	
	public function rewind()
	{
		reset($this->sourceToTarget);
	}
}