<?php
namespace Aquarium\Resources;


interface IConsumer
{
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
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function addView($path);
}