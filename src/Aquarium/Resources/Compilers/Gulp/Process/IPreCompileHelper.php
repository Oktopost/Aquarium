<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Package;
use Aquarium\Resources\Modules\Utils\ResourceMap;
use Aquarium\Resources\Compilers\Gulp\CompilerSetup;


interface IPreCompileHelper
{
	/**
	 * @param array $files Array of full files path
	 * @return array
	 */
	public function getTimestamps(array $files);
	
	/**
	 * @param Package $p
	 * @param ResourceMap $compilationMap Aggregated compilation map.
	 * @param array $modified Array of final Resource files that must be recompiled.
	 * @return CompilerSetup
	 */
	public function getRecompileTargets(Package $p, ResourceMap $compilationMap, array $modified);
}