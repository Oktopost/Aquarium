<?php
namespace Aquarium\Resources\Base\Config;


use Aquarium\Resources\Base\Log\ILogStream;
use Aquarium\Resources\Base\Log\ILogHandler;

use Skeleton\ISingleton;


/**
 * @skeleton
 */
interface ILogConfig extends ISingleton
{
	/**
	 * @param ILogStream $stream
	 * @return static
	 */
	public function setLogStream(ILogStream $stream);
	
	/**
	 * @param ILogHandler $handler
	 * @return static
	 */
	public function setLogger(ILogHandler $handler);
	
	/**
	 * @return ILogHandler
	 */
	public function log();
}