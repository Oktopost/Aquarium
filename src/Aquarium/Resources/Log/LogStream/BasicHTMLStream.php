<?php
namespace Aquarium\Resources\Log\LogStream;


use Aquarium\Resources\Base\Log\ILogStream;


class BasicHTMLStream implements ILogStream
{
	/**
	 * @param string $message
	 * @return static
	 */
	public function write($message)
	{
		echo $message;
		return $this;
	}
	
	/**
	 * @param string $message
	 * @return static
	 */
	public function writeLine($message)
	{
		$this->write($message);
		$this->emptyLine();
		return $this;
	}
	
	/**
	 * @param int $count
	 * @return static
	 */
	public function emptyLine($count = 1)
	{
		if ($count < 1)
			throw new \Exception('Count should not be less then 1. Got ' . $count);
			
		echo str_repeat("<br>\n", $count);
		return $this;
	}
}