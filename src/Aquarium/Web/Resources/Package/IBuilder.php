<?php
namespace Aquarium\Web\Resources\Package;


use Aquarium\Web\Resources\Package;


interface IBuilder
{
	/**
	 * @param Package $package
	 * @return static
	 */
	public function setup(Package $package);
	
	/**
	 * @param string $style
	 * @return static
	 */
	public function style($style);
	
	/**
	 * @param string $script
	 * @return static
	 */
	public function script($script);
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name);
}