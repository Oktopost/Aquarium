<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


use Aquarium\Resources\Modules\Utils\ResourceMap;
use Aquarium\Resources\Compilers\Gulp\CompilerSetup;


class PreCompileHelper
{
	use \Objection\TStaticClass;
	
	
	/**
	 * @param array $files Array of full files path
	 * @return array
	 */
	public static function getTimestamps(array $files)
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
	 * @param ResourceMap $compilationMap Aggregated compilation map.
	 * @param array $modified Array of final Resource files that must be recompiled.
	 * @return CompilerSetup
	 */
	public static function getRecompileTargets(ResourceMap $compilationMap, array $modified)
	{
		$recompileSource = [];
		$unmodifiedFlipped = array_flip(array_keys($compilationMap->getMap()));
		
		// Key is the original source file
		$recompileSourceFlipped = [];
		
		
		foreach ($modified as $resource)
		{
			unset($unmodifiedFlipped[$resource]);
			$resourceSources = $compilationMap->getMapFor($resource);
			
			// This should not happen. Modified file that didn't have a map should not be present here.
			if (is_null($resourceSources)) continue;
			
			if (!is_array($resourceSources)) $resourceSources = [$resourceSources];
			
			$recompileSourceFlipped = array_merge($recompileSourceFlipped, array_flip($resourceSources));
		}
		
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
		
		$setup = new CompilerSetup();
		
		if ($unmodifiedFlipped) $setup->Unchanged->add(array_keys($unmodifiedFlipped));
		if ($recompileSource) $setup->CompileTarget->add($recompileSource);
		
		return $setup;
	}
}