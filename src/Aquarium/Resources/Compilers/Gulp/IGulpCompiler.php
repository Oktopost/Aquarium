<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Utils\ResourceCollection;


interface IGulpCompiler
{
	/**
	 * @param GulpCompileConfig $config
	 * @return static
	 */
	public function setCompilerConfig(GulpCompileConfig $config);
	
	/**
	 * @param CompilerSetup $setup
	 * @return ResourceCollection
	 */
	public function compileStyle(CompilerSetup $setup);
	
	/**
	 * @param CompilerSetup $setup
	 * @return ResourceCollection
	 */
	public function compileScript(CompilerSetup $setup);
	
	/**
	 * @param CompilerSetup $setup
	 * @return ResourceCollection
	 */
	public function compileView(CompilerSetup $setup);
}