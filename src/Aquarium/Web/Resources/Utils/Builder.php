<?php
namespace Aquarium\Web\Resources\Utils;


use Aquarium\Web\Resources\Package;
use Aquarium\Web\Resources\Package\IBuilder;


class Builder implements IBuilder
{
	/** @var Package */
	private $package = null;
	
	
	/**
	 * @param Package $package
	 * @return static
	 */
	public function setup(Package $package) 
	{
		$this->package = $package;
		return $this;
	}
	
	/**
	 * @param string $style
	 * @return static
	 */
	public function style($style)
	{
		$this->package->Styles->add($style);
		return $this;
	}
	
	/**
	 * @param string $script
	 * @return static
	 */
	public function script($script)
	{
		$this->package->Scripts->add($script);
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name)
	{
		$this->package->Requires->add($name);
		return $this;
	}
}