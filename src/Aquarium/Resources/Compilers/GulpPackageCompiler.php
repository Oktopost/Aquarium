<?php
namespace Aquarium\Resources\Compilers;


use Aquarium\Resources\Base\State\IStateValidator;
use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\ICompiler;

use Aquarium\Resources\Compilation\IPhpBuilder;

use Aquarium\Resources\Compilers\Gulp\GulpCompileConfig;
use Aquarium\Resources\Compilers\Gulp\Process\GulpCompiler;
use Aquarium\Resources\Compilers\Gulp\Process\ICompileHelper;
use Aquarium\Resources\Compilers\Gulp\Process\PreCompiler;
use Aquarium\Resources\Compilers\Gulp\CompileConfig\ConfigBuilder;
use Objection\Mapper\Utils\ValuesProcessorCreator;


class GulpPackageCompiler implements ICompiler
{
	/** @var ConfigBuilder $configBuilder */
	private $configBuilder = null;
	
	/** @var GulpCompileConfig */
	private $compileConfig = null;
	
	/** @var IStateValidator */
	private $validator;
	
	
	public function __construct()
	{
		$this->configBuilder = new ConfigBuilder();
		$this->validator = Config::skeleton(IStateValidator::class);
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
			$this->compileConfig->TargetDirectory = Config::instance()->directories()->CompiledResourcesDir;
		}
		
		$compiled = new Package($package->Name);
		$compiled->Requires->add($package->Requires);
		
		$preCompiler = new PreCompiler();
		
		$preCompiler->setConfig($this->compileConfig);
		$viewSettings = $preCompiler->preCompileView($package);
		$styleSettings = $preCompiler->preCompileStyle($package);
		$scriptSettings = $preCompiler->preCompileScript($package);
		$compiler = new GulpCompiler();
		
		if (!$this->validator->isModified($package, $preCompiler->getTargetPackage()))
		{
			return $preCompiler->getTargetPackage(); 
		}
		
		$compiler->setCompilerConfig($this->compileConfig);
		
		$compiled->Views->add($compiler->compileView($viewSettings));
		$compiled->Styles->add($compiler->compileStyle($styleSettings));
		$compiled->Scripts->add($compiler->compileScript($scriptSettings));
		
		/** @var ICompileHelper $compilerHelper */
		$compilerHelper = Config::skeleton(ICompileHelper::class);
		$compilerHelper->cleanDirectory($compiled);
		
		$this->validator->saveNewState($package, $compiled);
		
		return $compiled;
	}
	
	/**
	 * @throws \Exception
	 */
	public function compile()
	{
		$packageDefinitionManager = Config::instance()->packageDefinitionManager();
		
		foreach ($packageDefinitionManager->getNames() as $name)
		{
			$originalPackage = $packageDefinitionManager->get($name);
			Config::log()->logPackageBuildStart($originalPackage);
			
			$compiledPackage = $this->compilePackage($originalPackage);
			
			/** @var IPhpBuilder $builder */
			$builder = Config::skeleton(IPhpBuilder::class);
			
			$builder->buildPhpFile($compiledPackage);
			
			Config::log()->logPackageBuildComplete($compiledPackage);
		}
	}
}