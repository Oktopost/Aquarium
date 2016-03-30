<?php
namespace Aquarium\Web\Resources;


interface IConsumer {
	
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