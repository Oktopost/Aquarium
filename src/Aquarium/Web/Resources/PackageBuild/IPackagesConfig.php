<?php
namespace Aquarium\Web\Resources\PackageBuild;


interface IPackagesConfig {
	
	/**
	 * @param string $packageName
	 * @param IBuilder $builder
	 */
	public function assemble($packageName, IBuilder $builder);
	
}