<?php
namespace Aquarium\Resources\Compilers\Gulp;


/**
 * Build gulp command.
 */
interface IGulpCommand
{
	/**
	 * @param string $path
	 * @return static
	 */
	public function setGulpPath($path);
	
	/**
	 * Set gulp action to execute.
	 * @param string $action
	 * @return static
	 */
	public function setAction($action);
	
	/**
	 * @param string $arg
	 * @param mixed|null $value
	 * @return static
	 */
	public function setArg($arg, $value = null);
	
	/**
	 * @param IShell $shell
	 * @throws GulpException When gulp exit with none zero code.
	 */
	public function execute(IShell $shell);
	
	/**
	 * @return string
	 */
	public function getOutput();
}