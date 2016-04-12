<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


interface ITimestampHelper
{
	/**
	 * @param string $source
	 * @return int|bool
	 */
	public function getTimestampFromFileName($source);
	
	/**
	 * Find the target file with timestamp data attached to it.
	 * @param string $source
	 * @return string|bool Return false string if file does not exists.
	 */
	public function findFileWithTimestamp($source);
	
	/**
	 * @param int|bool $time
	 * @return string
	 */
	public function generateTimestamp($time = false);
	
	/**
	 * @param string $file
	 * @param int|bool $time
	 * @return string|bool New file name with timestamp 
	 */
	public function generateTimestampForFile($file, $time = false);
}