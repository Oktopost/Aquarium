<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Modules\Utils\ResourceCollection;


interface IPreCompiler
{
	/**
	 * @param GulpCompileConfig $config
	 * @return static
	 */
	public function setConfig(GulpCompileConfig $config);
	
	/**
	 * @param ResourceCollection $collection
	 * @return CompilerSetup
	 */
	public function preCompileStyle(ResourceCollection $collection);
	
	/**
	 * @param ResourceCollection $collection
	 * @return CompilerSetup
	 */
	public function preCompileScript(ResourceCollection $collection);
}