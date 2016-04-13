<?php
namespace Aquarium\Resources\Compilers;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\ICompiler;

use Aquarium\Resources\Compilation\IPhpBuilder;

use Aquarium\Resources\Compilers\Gulp\GulpCompileConfig;
use Aquarium\Resources\Compilers\Gulp\Process\GulpCompiler;
use Aquarium\Resources\Compilers\Gulp\Process\ICompileHelper;
use Aquarium\Resources\Compilers\Gulp\Process\PreCompiler;
use Aquarium\Resources\Compilers\Gulp\CompileConfig\ConfigBuilder;


class GulpPackageManager implements ICompiler
{
	/** @var ConfigBuilder $configBuilder */
	private $configBuilder = null;
	
	/** @var GulpCompileConfig */
	private $compileConfig = null;
	
	
	public function __construct()
	{
		$this->configBuilder = new ConfigBuilder();
	}
	
	
	/**
	 * @return ConfigBuilder
	 */
	public function setup()
	{
		return $this->configBuilder;
	}
	
	
	/**
	 * @param Package $package
	 * @return Package Package containing compiled resources.
	 */
	public function compilePackage(Package $package)
	{
		if (!$this->compileConfig)
		{
			$this->compileConfig = $this->configBuilder->getConfig();
			$this->compileConfig->TargetDirectory = Config::instance()->Directories->ResourcesTargetDir;
		}
		
		$compiled = new Package($package->Name);
		
		
		$preCompiler = new PreCompiler();
		
		$preCompiler->setConfig($this->compileConfig);
		$styleSettings = $preCompiler->preCompileScript($package);
		$scriptSettings = $preCompiler->preCompileScript($package);
		
		$compiler = new GulpCompiler();
		
		$compiler->setCompilerConfig($this->compileConfig);
		$compiled->Scripts->add($compiler->compileScript($scriptSettings));
		$compiled->Styles->add($compiler->compileScript($styleSettings));
		
		/** @var ICompileHelper $compilerHelper */
		$compilerHelper = Config::skeleton(ICompileHelper::class);
		$compilerHelper->cleanDirectory($compiled);
		
		return $compiled;
	}
	
	/**
	 * @throws \Exception
	 */
	public function compile()
	{
		foreach (Config::instance()->DefinitionManager->getNames() as $name)
		{
			$package = $this->compilePackage(Config::instance()->DefinitionManager->get($name));
			
			/** @var IPhpBuilder $builder */
			$builder = Config::skeleton(IPhpBuilder::class);
			
			$builder->buildPhpFile($package);
		}
	}
}