<?php
namespace Aquarium\Resources\Base\Log;


use Aquarium\Resources\Package;


interface ILogHandler
{
	/**
	 * @param ILogStream $stream
	 * @return static
	 */
	public function setStream(ILogStream $stream);
	
	/**
	 * @param $message
	 * @return static
	 */
	public function logMessage($message);
	
	/**
	 * @param \Exception $e
	 * @param string|bool $message
	 * @return static
	 */
	public function logException(\Exception $e, $message = false);
	
	/**
	 * @param string $gulpOutput
	 * @param string|bool $message
	 * @return static
	 */
	public function logBuildException($gulpOutput, $message = false);
	
	/**
	 * @param Package $package
	 * @return static
	 */
	public function logPackageBuildStart(Package $package);
	
	/**
	 * @param Package $package
	 * @return static
	 */
	public function logPackageBuildComplete(Package $package);
}