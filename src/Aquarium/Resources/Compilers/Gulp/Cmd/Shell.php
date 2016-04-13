<?php
namespace Aquarium\Resources\Compilers\Gulp\Cmd;


use Aquarium\Resources\Compilers\Gulp\IShell;


/**
 * Execute shell command
 */
class Shell implements IShell
{
	/**
	 * @param string $command
	 * @param string $output
	 * @return int 
	 */
	public function execute($command, &$output) 
	{
		exec($command, $output, $result);
		$output = implode(PHP_EOL, $output);
		return $result;
	}
}