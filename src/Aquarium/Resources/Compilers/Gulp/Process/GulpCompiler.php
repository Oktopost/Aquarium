<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Compilers\Gulp\IShell;
use Aquarium\Resources\Utils\FileSystem;
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
	 */
	private function execute(Package $p, array $commands)
	{
		$targetDir = Config::instance()->Directories->ResourcesTargetDir . DIRECTORY_SEPARATOR . $p->getName('_');
		
		/** @var IGulpCommand $command */
		$command = Config::skeleton(IGulpCommand::class);
		
		$command->setAction('build');
		$command->setGulpPath(__DIR__ . '/../GulpScript/');
			
		if (!file_exists($targetDir)) {
			mkdir($targetDir, 0777, true);
		}
		
		$command->setArg('--commands', $commands);
		$command->setArg('--targetDir', $targetDir);
		
		$command->execute(Config::skeleton(IShell::class));
	}
	
	
	/**
	 * @param IGulpAction[] $actions
	 * @param CompilerSetup $setup
	 * @return ResourceCollection
	 */
	private function compile(array $actions, CompilerSetup $setup)
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
		
		foreach ($commands as $command)
		{
			$this->execute($setup->Package, [$command]);
		}
		
		if ($this->config->IsAddTimestamp)
		{
			foreach ($compiledCollection->get() as $compiledFile)
			{
				$timestampFile = $this->timeHelper->findFileWithTimestamp($compiledFile);
				
				if ($timestampFile && (new FileSystem())->isFilesSame($timestampFile, $compiledFile))
				{
					// Make sure on next compilation this file will not be affected.
					touch($timestampFile);
					$compiledCollection->replace($compiledFile, $timestampFile);
					continue;
				}
				
				if ($timestampFile)
					unlink($timestampFile);
				
				$timestampName = $this->timeHelper->generateTimestampForFile($compiledFile);
				$compiledCollection->replace($compiledFile, $timestampName);
				rename($compiledFile, $timestampName);
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
		return $this->compile($this->config->StyleActions, $setup);
	}
	
	/**
	 * @param CompilerSetup $setup
	 * @return ResourceCollection
	 */
	public function compileScript(CompilerSetup $setup) 
	{
		return $this->compile($this->config->ScriptActions, $setup);
	}
}