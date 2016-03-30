<?php
namespace Aquarium\Web\Resources;


interface IPackageProvider {
	
	/**
	 * @param IProvider $provider
	 * @return static
	 */
	public function setProvider(IProvider $provider);
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function addPackage($name);
}