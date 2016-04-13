<?php
namespace Aquarium\Resources\Compilers\Gulp\Cmd;


use Aquarium\Resources\Compilers\Gulp\IShell;
use Aquarium\Resources\Compilers\Gulp\IGulpCommand;
use Aquarium\Resources\Compilers\Gulp\GulpException;


class GulpCommand implements IGulpCommand
{
	private $path;
	private $output;
	private $action;
	
	private $arguments = [];
	
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function setGulpPath($path)
	{
		$this->path = $path;
		return $this;
	}
	
	/**
	 * Set gulp action to execute.
	 * @param string $action
	 * @return static
	 */
	public function setAction($action) 
	{
		$this->action = $action;
		return $this;
	}
	
	/**
	 * @param $arg
	 * @param null $value
	 * @return static
	 */
	public function setArg($arg, $value = null) 
	{
		if (!$value)
		{
			$this->arguments[] = $arg;
		}
		else 
		{
			if (is_array($value))
			{
				$value = json_encode($value, JSON_UNESCAPED_SLASHES);
				$value = str_replace('"', '\\"', $value);
			}
			
			$this->arguments[] = "$arg $value";
		}
		
		return $this;
	}
	
	/**
	 * @param IShell $shell
	 * @throws GulpException When gulp exit with none zero code.
	 */
	public function execute(IShell $shell) 
	{
		$commandParts = array_merge(
			["cd {$this->path} &&"],
			['gulp'],
			[$this->action],
			$this->arguments
		);
		
		$command = implode(' ', $commandParts);
		$result = $shell->execute($command, $this->output);
		
		if ($result !== 0) 
			throw new GulpException($result, $this->output);
	}
	
	/**
	 * @return string
	 */
	public function getOutput()
	{
		return $this->output;
	}
}