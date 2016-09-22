<?php
namespace Aquarium\Resources\Base\Log;


interface ILogStream
{
	/**
	 * @param string $message
	 * @return static
	 */
	public function write($message);
	
	/**
	 * @param string $message
	 * @return static
	 */
	public function writeLine($message);
	
	/**
	 * @param int $count
	 * @return static
	 */
	public function emptyLine($count = 1);
}