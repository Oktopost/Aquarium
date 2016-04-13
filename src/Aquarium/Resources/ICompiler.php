<?php
namespace Aquarium\Resources;


interface ICompiler
{
	/**
	 * @param Package $package
	 * @return Package Package containing compiled resources.
	 */
	public function compilePackage(Package $package);
	
	/**
	 * @throws \Exception
	 */
	public function compile();
}