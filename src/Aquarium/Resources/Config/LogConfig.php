<?php
namespace Aquarium\Resources\Config;


use Aquarium\Resources\Log\LogStream;
use Aquarium\Resources\Log\LogHandlers;

use Aquarium\Resources\Base\Log\ILogHandler;
use Aquarium\Resources\Base\Log\ILogStream;
use Aquarium\Resources\Base\Config\ILogConfig;


class LogConfig implements ILogConfig
{
	/** @var ILogStream */
	private $stream = null;
	
	/** @var ILogHandler */
	private $log = null;
	
	
	private function loadLoggerForPHPInterface()
	{
		if (!$this->stream)
		{
			$this->stream = (php_sapi_name() == 'cli' ? 
				new LogStream\ConsoleLogStream() :
				new LogStream\BasicHTMLStream());
		}
		
		if (!$this->log)
		{
			$this->log = (php_sapi_name() == 'cli' ?
				new LogHandlers\SimpleConsoleHandler() :
				new LogHandlers\SimpleHTMLHandler());
		}
		
		$this->log->setStream($this->stream);
	}
	
	
	/**
	 * @param ILogStream $stream
	 * @return static
	 */
	public function setLogStream(ILogStream $stream)
	{
		$this->stream = $stream;
		
		if ($this->log)
			$this->log->setStream($stream);
		
		return $this;
	}
	
	/**
	 * @param ILogHandler $handler
	 * @return static
	 */
	public function setLogger(ILogHandler $handler)
	{
		$this->log = $handler;
		
		if ($this->stream)
			$this->log->setStream($this->stream);
		
		return $this;
	}
	
	/**
	 * @return ILogHandler
	 */
	public function log()
	{
		if (!$this->stream || !$this->log)
		{
			$this->loadLoggerForPHPInterface();
		}
		
		return $this->log;
	}
}