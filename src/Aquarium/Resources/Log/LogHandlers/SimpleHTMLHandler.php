<?php
namespace Aquarium\Resources\Log\LogHandlers;


use Aquarium\Resources\Package;
use Aquarium\Resources\Base\Log\AbstractLogHandler;


class SimpleHTMLHandler extends AbstractLogHandler
{
	private $startTime = 0.0;
	
	
	/**
	 * @param \Exception $e
	 * @param string|bool $message
	 * @return static
	 */
	public function logException(\Exception $e, $message = false)
	{
		$this->stream()->emptyLine();
		$this->stream()->write('<hr>');
		
		if ($message)
		{
			$this->stream()->emptyLine();
			$this->stream()->writeLine($message);
		}
		
		$this->stream()->emptyLine();
		$this->stream()->writeLine("Err Message: {$e->getMessage()}");
		$this->stream()->writeLine("At: {$e->getFile()}");
		$this->stream()->writeLine("Line: {$e->getLine()}");
		
		if ($e->getCode() != 0)
			$this->stream()->writeLine("Code: {$e->getLine()}");
		
		$this->stream()->writeLine('Stack: ');
		$this->stream()->emptyLine();
		$this->stream()->writeLine("<ol>");
		
		foreach (explode("\n", $e->getTraceAsString()) as $line)
		{
			$line = str_replace("\r", '', $line);
			$this->stream()->writeLine("<li>$line</li>");
		}
		
		$this->stream()->writeLine("</ol>");
		$this->stream()->emptyLine();
		$this->stream()->write('<hr>');
		$this->stream()->emptyLine();
		
		return $this;
	}
	
	/**
	 * @param string $gulpOutput
	 * @param string|bool $message
	 * @return static
	 */
	public function logBuildException($gulpOutput, $message = false)
	{
		$this->stream()->emptyLine();
		$this->stream()->write('<hr>');
		
		if ($message)
		{
			$this->stream()->emptyLine();
			$this->stream()->writeLine($message);
		}
		
		$this->stream()->emptyLine();
		$this->stream()->writeLine("Gulp build failed with the following error");
		$this->stream()->emptyLine();
		$this->stream()->writeLine("<ol>");
		
		foreach (explode("\n", $gulpOutput) as $line)
		{
			$line = str_replace("\r", '', $line);
			$this->stream()->writeLine("<li>$line</li>");
		}
		
		$this->stream()->writeLine("</ol>");
		$this->stream()->emptyLine();
		$this->stream()->write('<hr>');
		$this->stream()->emptyLine();
		
		return $this;
	}
	
	/**
	 * @param Package $package
	 * @return static
	 */
	public function logPackageBuildStart(Package $package)
	{
		$this->startTime = round(microtime(true), 4);
		$this->stream()->write("Building {$package->Name}");
		return $this;
	}
	
	/**
	 * @param Package $package
	 * @return static
	 */
	public function logPackageBuildComplete(Package $package)
	{
		$runTime = round(abs(round(microtime(true), 4) - $this->startTime), 4);
		$this->stream()->writeLine(" ... complete in $runTime sec");
		return $this;
	}
}