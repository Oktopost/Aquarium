<?php
namespace Aquarium\Resources\Package;


use Aquarium\Resources\Package;


interface IBuilder
{
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