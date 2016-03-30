<?php
namespace Aquarium\Web\Resources;


use \Aquarium\Web\Resources\PackageBuild\IPackagesConfig;


interface IConfig {
	
	/**
	 * @return IPackagesConfig
	 */
	public function getPackagesConfig();
	
	public function getProvider();
}