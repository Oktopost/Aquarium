<?php
namespace Aquarium\Resources\Compilers\Gulp;


/**
 * Execute shell command
 */
interface IShell
{
	/**
	 * @param string $command
	 * @param string $output
	 * @return int 
	 */
	public function execute($command, &$output);
}