<?php
namespace Aquarium\Resources\Log\LogStream;


use Aquarium\Resources\Base\Log\ILogStream;


/**
 * For unit tests.
 */
class VoidStream implements ILogStream
{
	public function write($message) {}
	public function writeLine($message) {}
	public function emptyLine($count = 1) {}
}