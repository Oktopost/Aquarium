<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Compilers\Gulp\IShell;

use Aquarium\Resources\Utils\ResourceCollection;

use Aquarium\Resources\Compilers\Gulp\IGulpAction;
use Aquarium\Resources\Compilers\Gulp\IGulpCommand;
use Aquarium\Resources\Compilers\Gulp\IGulpCompiler;
use Aquarium\Resources\Compilers\Gulp\CompilerSetup;
use Aquarium\Resources\Compilers\Gulp\GulpCompileConfig;


class GulpCompiler implements IGulpCompiler
{
	/** @var GulpCompileConfig $config */
	private $config;
	
	/** @var ITimestampHelper $timeHelper */
	private $timeHelper;
	
	
	/**
	 * @param Package $p
	 * @param array $commands
	 * @param array $files
	 */
	private function execute(Package $p, array $commands, array $files)
	{
		$targetDir = Config::instance()->Directories->ResourcesTargetDir . DIRECTORY_SEPARATOR . $p->getName('_');
		
		/** @var IGulpCommand $command */
		$command = Config::skeleton(IGulpCommand::class);
		
		$command->setAction('build');
		$command->setGulpPath(__DIR__ . '/../GulpScript/');
			
		if (!file_exists($targetDir)) {
			mkdir($targetDir, 0777, true);
		}
		
		$command->setArg('commands', $commands);
		$command->setArg('targetDir', $targetDir);
		$command->setArg('source', $files);
		
		$command->execute(Config::skeleton(IShell::class));
	}
	
	
	/**
	 * @param IGulpAction[] $actions
	 * @param CompilerSetup $setup
	 * @param array $files
	 * @return ResourceCollection
	 */
	private function compile(array $actions, CompilerSetup $setup, array $files)
	{
		if (!$actions)
			return clone ($setup->Unchanged->add($setup->CompileTarget));
		
		$commands = [];
		$compiledCollection = clone $setup->CompileTarget;
		
		foreach ($actions as $action)
		{
			$action->setTargetDir($this->config->TargetDirectory);
			
			$map = $action->getMap($setup->Package, $compiledCollection);
			$commands[] = $action->getCommand($setup->Package, $compiledCollection);
			
			$map->apply($compiledCollection);
		}
		
		$this->execute($setup->Package, $commands, $files);
		
		if ($this->config->IsAddTimestamp)
		{
			foreach (array_diff($compiledCollection->get(), $setup->CompileTarget->get()) as $compiledFile)
			{
				$compiledCollection->replace($compiledFile, $this->timeHelper->findFileWithTimestamp($compiledFile));
			}
		}
		
		$compiledCollection->add($setup->Unchanged);
		
		return $compiledCollection;
	}
	
	
	public function __construct()
	{
		$this->timeHelper = Config::skeleton(ITimestampHelper::class);
	}
	
	
	/**
	 * @param GulpCompileConfig $config
	 * @return static
	 */
	public function setCompilerConfig(GulpCompileConfig $config)
	{
		$this->config = $config;
		return $this;
	} 
	
	/**
	 * @param CompilerSetup $setup
	 * @return ResourceCollection
	 */
	public function compileStyle(CompilerSetup $setup)
	{
		return $this->compile($this->config->StyleActions, $setup, $setup->Package->Styles->get());
	}
	
	/**
	 * @param CompilerSetup $setup
	 * @return ResourceCollection
	 */
	public function compileScript(CompilerSetup $setup) 
	{
		return $this->compile($this->config->ScriptActions, $setup, $setup->Package->Scripts->get());
	}
}