<?php
namespace Aquarium\Resources;


interface IProvider
{
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name);
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function script($path);
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function style($path);
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function view($path);
}