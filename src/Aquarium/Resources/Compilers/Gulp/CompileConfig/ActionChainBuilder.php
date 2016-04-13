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
	public function scss() 
	{
		$this->collection[] = $this->factory->scss();
	}
	
	/**
	 * @param string|bool $filter
	 * @return IGulpAction
	 */
	public function concatenate($filter = false)
	{
		$this->collection[] = $this->factory->concatenate($filter);
	}
	
	/**
	 * @return IGulpAction
	 */
	public function jsmin() 
	{
		$this->collection[] = $this->factory->jsmin();
	}
	
	/**
	 * @return IGulpAction
	 */
	public function cssmin()
	{
		$this->collection[] = $this->factory->cssmin();
	}
	
	
	/**
	 * @return IGulpAction[]
	 */
	public function getCollection()
	{
		return $this->collection;
	}
}