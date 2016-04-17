<?php
namespace Aquarium\Resources;


use Psr\Log\LoggerInterface;


class CmdLogger implements LoggerInterface
{
	/**
	 * @param string $type
	 * @param string $message
	 * @param array $context
	 */
	private function println($type, $message, array $context = array())
	{
		$context = ($context ? json_encode($context) : '');
		print "$type: $message $context";
	}
	
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function emergency($message, array $context = array())
	{
		$this->println('emergency', $message, $context);
	}
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function alert($message, array $context = array())
	{
		$this->println('alert', $message, $context);
	}
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function critical($message, array $context = array())
	{
		$this->println('critical', $message, $context);
	}
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function error($message, array $context = array())
	{
		$this->println('error', $message, $context);
	}
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function warning($message, array $context = array())
	{
		$this->println('warning', $message, $context);
	}
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function notice($message, array $context = array())
	{
		$this->println('notice', $message, $context);
	}
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function info($message, array $context = array())
	{
		$this->println('info', $message, $context);
	}
	
	/**
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function debug($message, array $context = array())
	{
		$this->println('debug', $message, $context);
	}
	
	/**
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log($level, $message, array $context = array())
	{
		$this->println('log', $message, $context);
	}
}