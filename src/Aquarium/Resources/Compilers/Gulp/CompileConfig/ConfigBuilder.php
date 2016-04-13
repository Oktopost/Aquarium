<?php
namespace Aquarium\Resources\Compilers\Gulp\CompileConfig;


use Aquarium\Resources\Config;
use Aquarium\Resources\Compilers\Gulp\GulpCompileConfig;


class ConfigBuilder
{
	/** @var ActionChainBuilder */
	private $styleChain;
	
	/** @var ActionChainBuilder */
	private $scriptChain;
	
	/** @var GulpCompileConfig */
	private $configSetup;
	
	
	public function __construct()
	{
		$this->configSetup = new GulpCompileConfig();
	}
	
	/**
	 * @return ActionChainBuilder
	 */
	public function style()
	{
		return $this->styleChain;
	}
	
	/**
	 * @return ActionChainBuilder
	 */
	public function script()
	{
		return $this->styleChain;
	}
	
	/**
	 * @return static
	 */
	public function addTimestamp()
	{
		$this->configSetup->IsAddTimestamp = true;
		return $this;
	}
	
	/**
	 * @return GulpCompileConfig
	 */
	public function getConfig()
	{
		$this->configSetup->addStyleAction($this->styleChain->getCollection());
		$this->configSetup->addScriptAction($this->scriptChain->getCollection());
		
		return $this->configSetup;
	}
}