<?php
namespace Aquarium\Resources\Compilers\Gulp;


class GulpException extends \Exception
{
	private $output;
	
	
	/**
	 * @param int $returnCode
	 * @param string $output
	 */
	public function __construct($returnCode, $output)
	{
		$this->output = $output;
		parent::__construct("Gulp exit with none zero code: $returnCode", $returnCode);
	}
	
	
	/**
	 * @return string
	 */
	public function getOutput() 
	{
		return $this->output;
	}
}