<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Config;
use Aquarium\Resources\Modules\Utils\ResourceCollection;
use Aquarium\Resources\Compilers\Gulp\IGulpAction;
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
			$map = $action->getMap($compiledCollection);
			
			$commands[] = $action->getCommand($compiledCollection);
			
			$map->apply($compiledCollection);
		}
		
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