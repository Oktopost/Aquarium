<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Compilers\Gulp\Actions\HandleBarAction;
use Aquarium\Resources\Config;
use Aquarium\Resources\Package;

use Aquarium\Resources\Compilers\Gulp\IGulpAction;
use Aquarium\Resources\Compilers\Gulp\IPreCompiler;
use Aquarium\Resources\Compilers\Gulp\CompilerSetup;
use Aquarium\Resources\Compilers\Gulp\GulpCompileConfig;
use Aquarium\Resources\Utils\FileSystem;
use Aquarium\Resources\Utils\ResourceMap;
use Aquarium\Resources\Utils\ResourceCollection;


class PreCompiler implements IPreCompiler
{
	/** @var GulpCompileConfig */
	private $config;
	
	/** @var IPreCompileHelper */
	private $preCompileHelper;
	
	/** @var ITimestampHelper */
	private $timestampHelper;
	
	/** @var Package */
	private $targetPackage = null;
	
	
	private function createTargetPackage(Package $source)
	{
		if ($this->targetPackage) return;
		
		$this->targetPackage = new Package($source->Name);
	}
	
	
	public function __construct()
	{
		$this->preCompileHelper = Config::skeleton(IPreCompileHelper::class); 
		$this->timestampHelper = Config::skeleton(ITimestampHelper::class); 
	}
	
	
	/**
	 * @return Package
	 */
	public function getTargetPackage()
	{
		return $this->targetPackage;
	}
	
	/**
	 * @param Package $p
	 * @param IGulpAction[] $actions
	 * @param ResourceCollection $collection
	 * @param ResourceCollection $target
	 * @return CompilerSetup
	 */
	private function preCompileActions(Package $p, array $actions, ResourceCollection $collection, ResourceCollection $target)
	{
		$modifiedCollection = clone $collection;
		
		/** @var ResourceMap $modifiedMap */
		$modifiedMap = new ResourceMap();
		
		// Detect changes.
		foreach ($actions as $action)
		{
			$action->setTargetDir($this->config->TargetDirectory);
			$map = $action->getMap($p, $modifiedCollection);
			
			$map->apply($modifiedCollection);
			
			$modifiedMap->modify($map);
		}
		
		$target->add($modifiedCollection);
		
		if ($this->config->IsAddTimestamp)
		{
			foreach ($target->get() as $file)
			{
				$timestampFile = $this->timestampHelper->findFileWithTimestamp($file);
				
				if (!$timestampFile)
				{
					$target->remove($file);
				}
				else
				{
					$target->replace($file, $timestampFile);
				}
			}
		}
		
		$setup = new CompilerSetup($p);
		$setup->CompileTarget->add($collection);
		
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
		$this->createTargetPackage($p);
		
		$unroll = new PackageUnroll(Config::instance()->packageDefinitionManager());
		$unroll->setOriginPackage($p);
		$styles = $unroll->getStyles();
			
		return $this->preCompileActions($p, $this->config->StyleActions, $styles, $this->targetPackage->Styles);
	}
	
	/**
	 * @param Package $p
	 * @return CompilerSetup
	 */
	public function preCompileScript(Package $p)
	{
		$this->createTargetPackage($p);
		
		$unroll = new PackageUnroll(Config::instance()->packageDefinitionManager());
		$unroll->setOriginPackage($p);
		$scripts = $unroll->getScripts();
		
		return $this->preCompileActions($p, $this->config->ScriptActions, $scripts, $this->targetPackage->Scripts);
	}
	
	/**
	 * @param Package $p
	 * @return CompilerSetup
	 */
	public function preCompileView(Package $p)
	{
		$this->createTargetPackage($p);
		
		$unroll = new PackageUnroll(Config::instance()->packageDefinitionManager());
		$unroll->setOriginPackage($p);
		$views = $unroll->getViews();
		
		return $this->preCompileActions($p, [new HandleBarAction()], $views, $this->targetPackage->Views);
	}
}