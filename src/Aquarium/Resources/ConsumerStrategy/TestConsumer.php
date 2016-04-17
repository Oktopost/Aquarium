<?php
namespace Aquarium\Resources\ConsumerStrategy;


use Aquarium\Resources\IConsumer;


class TestConsumer implements IConsumer
{
	public $scripts = [];
	public $styles = [];
	
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function addScript($path)
	{
		$this->scripts[] = $path;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function addStyle($path)
	{
		$this->styles[] = $path;
	}
}