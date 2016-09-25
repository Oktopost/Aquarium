<?php
namespace Aquarium\Resources\Compilers\Gulp\CompileConfig;


use Aquarium\Resources\Config;
use Aquarium\Resources\Compilers\Gulp\IGulpAction;
use Aquarium\Resources\Compilers\Gulp\IGulpActionFactory;


class ActionChainBuilder 
{
	/** @var IGulpActionFactory */
	private $factory;
	
	/** @var  IGulpAction[] */
	private $collection = [];
	
	
	public function __construct()
	{
		$this->factory = Config::skeleton(IGulpActionFactory::class);
	}
	
	
	/**
	 * @return static
	 */
	public function sass() 
	{
		$this->collection[] = $this->factory->sass();
		return $this;
	}
	
	/**
	 * @param string|bool $filter
	 * @return static
	 */
	public function concatenate($filter = false)
	{
		$this->collection[] = $this->factory->concatenate($filter);
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function jsmin() 
	{
		$this->collection[] = $this->factory->jsmin();
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function cssmin()
	{
		$this->collection[] = $this->factory->cssmin();
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function handleBar()
	{
		$this->collection[] = $this->factory->handleBar();
		return $this;
	}
	
	
	/**
	 * @return IGulpAction[]
	 */
	public function getCollection()
	{
		return $this->collection;
	}
}