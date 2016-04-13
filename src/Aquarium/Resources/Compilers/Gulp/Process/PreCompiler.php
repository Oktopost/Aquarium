<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Config;

use Aquarium\Resources\Compilers\Gulp\IGulpAction;
use Aquarium\Resources\Compilers\Gulp\IPreCompiler;
use Aquarium\Resources\Compilers\Gulp\CompilerSetup;
use Aquarium\Resources\Compilers\Gulp\GulpCompileConfig;

use Aquarium\Resources\Modules\Utils\ResourceMap;
use Aquarium\Resources\Modules\Utils\ResourceCollection;
use Aquarium\Resources\Package;


class PreCompiler implements IPreCompiler
{
	/** @var GulpCompileConfig */
	private $config;
	
	/** @var IPreCompileHelper */
	private $preCompileHelper;
	
	/** @var ITimestampHelper */
	private $timestampHelper;
	
	
	public function __construct()
	{
		$this->preCompileHelper = Config::skeleton(IPreCompileHelper::class); 
		$this->timestampHelper = Config::skeleton(ITimestampHelper::class); 
	}
	
	
	/**
	 * @param ResourceMap $map
	 * @return array
	 */
	private function getModified(ResourceMap $map)
	{
		$modified = [];
		
		foreach ($map->getMap() as $compiledResource => $sourceFiles)
		{
			if (!is_array($sourceFiles)) $sourceFiles = [$sourceFiles];
			
			$maxTimestamp = max(array_values($this->preCompileHelper->getTimestamps($sourceFiles)));
			$compiledTimestamp = 0;
			
			if ($this->config->IsAddTimestamp)
			{
				$compiledWithTimestamp = $this->timestampHelper->findFileWithTimestamp($compiledResource);
				
				if ($compiledWithTimestamp)
					$compiledTimestamp = $this->timestampHelper->getTimestampFromFileName($compiledWithTimestamp);
			}
			else
			{
				$data = $this->preCompileHelper->getTimestamps([$compiledResource]);
				$compiledTimestamp = end($data);
			}
			
			if ($maxTimestamp > $compiledTimestamp)
				$modified[] = $compiledResource;
		}
		
		return $modified;
	}
	
	/**
	 * @param Package $p
	 * @param IGulpAction[] $actions
	 * @param ResourceCollection $collection
	 * @return CompilerSetup
	 */
	private function preCompileActions(Package $p, array $actions, ResourceCollection $collection)
	{
		$modifiedCollection = clone $collection;
		
		/** @var ResourceMap $modifiedMap */
		$modifiedMap = null;
		
		
		// Detect changes.
		foreach ($actions as $action)
		{
			$action->setTargetDir($this->config->TargetDirectory);
			$map = $action->getMap($p, $modifiedCollection);
			
			$map->apply($modifiedCollection);
			
			if (is_null($modifiedMap)) $modifiedMap = $map;
			else $modifiedMap->modify($map); 
		}
		
		$modified = $this->getModified($modifiedMap);
		$setup = $this->preCompileHelper->getRecompileTargets($p, $modifiedMap, $modified);
		
		
		// All resource files that have an unmodified target resource.
		$unmodifiedSourceFiles = [];
		
		foreach ($setup->Unchanged as $unmodifiedTarget)
		{
			$data = $modifiedMap->getMapFor($unmodifiedTarget);
			$unmodifiedSourceFiles = array_merge($unmodifiedSourceFiles, (is_array($data) ? $data : [$data]));
		}
		
		
		// Upend source resource files that were not used to create any package.
		foreach ($collection as $resource)
		{
			// File will be used to recompile.
			if ($setup->CompileTarget->hasResource($resource))
				continue;
			
			// File used for a library that was not modified.
			if (in_array($resource, $unmodifiedSourceFiles))
				continue;
			
			// Else file is not modified at all.
			$setup->CompileTarget->add($resource);
		}
		
		
		// Rename target files to target files with timestamp.
		if ($this->config->IsAddTimestamp)
		{
			foreach ($setup->Unchanged->get() as $unchanged)
			{
				$setup->Unchanged->replace($unchanged, $this->timestampHelper->findFileWithTimestamp($unchanged));
			}
		}
		
		
		return $setup;
	}
	
	
	/**
	 * @param GulpCompileConfig $config
	 * @return static
	 */
	public function setConfig(GulpCompileConfig $config)
	{
		$this->config = $config;
		return $this;
	}
	
	/**
	 * @param Package $p
	 * @return CompilerSetup
	 */
	public function preCompileStyle(Package $p)
	{
		return $this->preCompileActions($p, $this->config->StyleActions, $p->Styles);
	}
	
	/**
	 * @param Package $p
	 * @return CompilerSetup
	 */
	public function preCompileScript(Package $p)
	{
		return $this->preCompileActions($p, $this->config->ScriptActions, $p->Scripts);
	}
}