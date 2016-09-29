<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceMap;
use Aquarium\Resources\Compilers\Gulp\CompilerSetup;
use Aquarium\Resources\Compilers\Gulp\Process\IPreCompileHelper;


class PreCompileHelper implements IPreCompileHelper
{
	/**
	 * @param array $files Array of full files path
	 * @return array
	 */
	public function getTimestamps(array $files)
	{
		$timestamps = [];
		
		foreach ($files as $file)
		{
			if (!file_exists($file)) 
			{
				$timestamps[$file] = 0;
			}
			else
			{
				$timestamps[$file] = filemtime($file);
			}
		}
		
		return $timestamps;
	}
	
	/**
	 * @param Package $p
	 * @param ResourceMap $compilationMap Aggregated compilation map.
	 * @return CompilerSetup
	 */
	public function getRecompileTargets(Package $p, ResourceMap $compilationMap)
	{
		$recompileSource = [];
		$unmodifiedFlipped = array_flip(array_keys($compilationMap->getMap()));
		
		// Key is the original source file
		$recompileSourceFlipped = [];
		
		
		while (count($recompileSource) < count($recompileSourceFlipped)) 
		{
			$recompileSource = array_keys($recompileSourceFlipped);
			
			// Find all 
			foreach (array_keys($unmodifiedFlipped) as $unmodifiedTarget) 
			{
				$sourceFiles = $compilationMap->getMapFor($unmodifiedTarget);
				
				if (!is_array($sourceFiles)) 
					$sourceFiles = [$sourceFiles];
				
				if (array_intersect($sourceFiles, $recompileSource))
				{
					unset($unmodifiedFlipped[$unmodifiedTarget]);
					$recompileSourceFlipped = array_merge($recompileSourceFlipped, array_flip($sourceFiles));
				}
			}
		}
		
		$setup = new CompilerSetup($p);
		
		if ($recompileSource) 
		{
			$setup->CompileTarget->add($recompileSource);
		}
		
		return $setup;
	}
}