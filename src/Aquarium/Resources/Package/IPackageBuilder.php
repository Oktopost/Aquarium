<?php
namespace Aquarium\Resources\Package;


interface IPackageBuilder
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
	 * @param string $view
	 * @return static
	 */
	public function view($view);
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name);
	
	/**
	 * @param string $package
	 * @return static
	 */
	public function inscribe($package);
}