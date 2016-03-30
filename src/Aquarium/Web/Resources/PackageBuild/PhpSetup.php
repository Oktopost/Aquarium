<?php
namespace Aquarium\Web\Resources\PackageBuild;


/**
 * @todo <alexey>
 */
class PhpSetup implements IPackagesConfig {
	
	/**
	 * @param string $name
	 * @param IBuilder $builder
	 */
	public function assemble($name, IBuilder $builder) {
		$config = $this->getPackagesConfigClass($name);
		$config->$name($builder);
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	private function getPackagesConfigClass($name) {
		return null;
	}
}