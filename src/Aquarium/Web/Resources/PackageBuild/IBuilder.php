<?php
namespace Aquarium\Web\Resources\PackageBuild;


interface IBuilder {
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function addPackage($name);
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function addScript($path);
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function addStyle($path);
}