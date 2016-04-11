<?php
namespace Aquarium\Resources\Compilers;


use Aquarium\Resources\Config;
use Aquarium\Resources\Compilers\Gulp\GulpCommand;
use Aquarium\Resources\Compilation\ICompiler;
use Aquarium\Resources\Compilation\IPhpBuilder;
use Aquarium\Resources\Compilation\DefaultPhpBuilder;


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
	 * @param IPhpBuilder $builder
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