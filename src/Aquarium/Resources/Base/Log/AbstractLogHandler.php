<?php
namespace Aquarium\Resources\Base\Log;


use Aquarium\Resources\Package;


abstract class AbstractLogHandler implements ILogHandler
{
	/** @var ILogStream */
	private $stream;
	
	
	/**
	 * @return ILogStream
	 */
	protected function stream()
	{
		return $this->stream;
	}
	
	
	/**
	 * @param ILogStream $stream
	 * @return static
	 */
	public function setStream(ILogStream $stream)
	{
		$this->stream = $stream;
		return $this;
	}
	
	/**
	 * @param $message
	 * @return static
	 */
	public function logMessage($message)
	{
		$this->stream->writeLine($message);
	}
}