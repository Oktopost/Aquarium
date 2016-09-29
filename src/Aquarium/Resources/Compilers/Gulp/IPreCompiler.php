<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Package;


interface IPreCompiler
{
	/**
	 * @param GulpCompileConfig $config
	 * @return static
	 */
	public function setConfig(GulpCompileConfig $config);
	
	/**
	 * @return Package
	 */
	public function getTargetPackage();
	
	/**
	 * @param Package $p
	 * @return CompilerSetup
	 */
	public function preCompileStyle(Package $p);
	
	/**
	 * @param Package $p
	 * @return CompilerSetup
	 */
	public function preCompileScript(Package $p);
	
	/**
	 * @param Package $p
	 * @return CompilerSetup
	 */
	public function preCompileView(Package $p);
}