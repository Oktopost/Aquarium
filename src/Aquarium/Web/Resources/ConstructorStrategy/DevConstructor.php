<?php
namespace Aquarium\Web\Resources\ConstructorStrategy;


use Aquarium\Web\Resources\Config;
use Aquarium\Web\Resources\Package;
use Aquarium\Web\Resources\Package\IConstructor;
use Aquarium\Web\Resources\Package\PackageDefinition;


class DevConstructor implements IConstructor
{
	/**
	 * @param Package $p
	 * @param PackageDefinition $pd
	 */
	private function loadPackageFilesIntoDefinition(Package $p, PackageDefinition $pd)
	{
		foreach ($p->Styles as $style)
		{
			if ($pd->hasStyle($style))
				throw new \Exception("Style [$style] is already present in package {$pd->Package->Name}");
			
			$pd->Styles[] = $style;
		}
		
		foreach ($p->Scripts as $script)
		{
			if ($pd->hasStyle($script))
				throw new \Exception("Script [$script] is already present in package {$pd->Package->Name}");
			
			$pd->Scripts[] = $script;
		}
	}
	
	/**
	 * @param Package $p
	 * @param PackageDefinition $pd
	 */
	private function loadPackage(Package $p, PackageDefinition $pd)
	{
		foreach ($p->Packages as $packageName) 
		{
			if ($pd->hasPackage($packageName)) continue;
			
			$this->loadPackageFilesIntoDefinition(
				Config::instance()->DefinitionManager->get($packageName),
				$pd);
		}
		
		$this->loadPackageFilesIntoDefinition($p, $pd);
	}
	
	
	/**
	 * @param $packageName
	 * @return PackageDefinition
	 */
	public function construct($packageName)
	{
		$package = Config::instance()->DefinitionManager->get($packageName);
		$definition = new PackageDefinition($package); 
		
		$this->loadPackage($package, $definition);
		
		return $definition;
	}
	
	/**
	 * Construct the package and append it to the consumer.
	 * @param $packageName
	 */
	public function append($packageName)
	{
		$definition = $this->construct($packageName);
		
		foreach ($definition->Styles as $style)
		{
			Config::instance()->Consumer->addStyle($style);
		}
		
		foreach ($definition->Scripts as $script)
		{
			Config::instance()->Consumer->addScript($script);
		}
	}
}