<?php
namespace Aquarium\Web\Resources\Compilers\Gulp;


use Aquarium\Web\Resources\Config;
use Aquarium\Web\Resources\Package;
use Aquarium\Web\Resources\Utils\ResourceCollection;


class GulpCommand
{
	/**
	 * @param ResourceCollection $resources
	 * @return array
	 */
	private function fixResourcePath(ResourceCollection $resources) 
	{
		$result = [];
		
		foreach ($resources as $resource)
		{
			$found = false;
			
			foreach (Config::instance()->Directories->ResourcesSourceDirs as $dir) 
			{
				if (!file_exists($dir . DIRECTORY_SEPARATOR . $resource)) continue;
				
				$result[] = $dir . DIRECTORY_SEPARATOR . $resource;
				$found = true;
				break;
			}
			
			if (!$found) 
				throw new \Exception("Could not find resource '$resource'");
		}
		
		return $result;
	}
	
	/**
	 * @param array $args
	 * @return string
	 */
	private function createCommand(array $args) 
	{
		$path = __DIR__;
		$command = "cd $path/Gulp && gulp build ";
		
		foreach ($args as $name => $value) {
			$command .= "--$name $value ";
		}
		
		return str_replace('"', '\\"', $command);
	}
	
	
	/**
	 * @param Package $p
	 * @throws \Exception If gulp returned non zero result.
	 * @return Package
	 */
	public function execute(Package $p)
	{
		$js		= $this->fixResourcePath($p->Scripts);
		$css	= $this->fixResourcePath($p->Styles);
		
		$command = $this->createCommand([
			'js'			=> json_encode($js, JSON_UNESCAPED_SLASHES),
			'css'			=> json_encode($css, JSON_UNESCAPED_SLASHES),
			'js-target' 	=> $p->getName('') . '.js',
			'css-target' 	=> $p->getName('') . '.css',
			'dir'			=> Config::instance()->Directories->ResourcesTargetDir
		]);
		
		system($command, $result);
		
		if ($result !== 0) 
			throw new \Exception('Gulp build failed. Aborting compilation');
		
		$compiled = new Package($p->Name);
		
		if ($css) $compiled->Styles = $p->getName('') . '.css';
		if ($js) $compiled->Scripts = $p->getName('') . '.js';
		
		return $compiled;
	}
}