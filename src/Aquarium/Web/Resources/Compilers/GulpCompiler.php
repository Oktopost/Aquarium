<?php
namespace Aquarium\Web\Resources\Compilers;


use Aquarium\Web\Resources\Compilation\DefaultPhpBuilder;
use Aquarium\Web\Resources\Compilation\IPhpBuilder;
use Aquarium\Web\Resources\Config;
use Aquarium\Web\Resources\Compilers\Gulp\GulpCommand;
use Aquarium\Web\Resources\Compilation\ICompiler;


class GulpCompiler implements ICompiler
{
	/** @var GulpCommand */
	private $gulpCommand;
	
	/** @var DefaultPhpBuilder */
	private $builder;
	
	
	/**
	 * @param string $name
	 */
	private function compilePackage($name)
	{
		$package = Config::instance()->DefinitionManager->get($name);
		$compiled = $this->gulpCommand->execute($package);
		$this->builder->buildPhpFile($compiled);
	}
	
	
	/**
	 * @param GulpCommand $command
	 */
	public function __construct(GulpCommand $command = null, IPhpBuilder $builder = null)
	{
		$this->gulpCommand = ($command ?: new GulpCommand());
		$this->builder = ($builder ?: new DefaultPhpBuilder());
	}
	
	
	/**
	 * @throws \Exception
	 */
	public function compile()
	{
		foreach (Config::instance()->DefinitionManager->getNames() as $name)
		{
			$this->compilePackage($name);
		}
	}
}