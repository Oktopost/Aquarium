<?php
namespace Aquarium\Resources\Base\State;


use Aquarium\Resources\State\PackageResourceSet;


/**
 * @skeleton
 */
interface IStateDAO
{
	/**
	 * @param string $name
	 * @return PackageResourceSet
	 */
	public function getPackageState($name);
	
	/**
	 * @param PackageResourceSet $set
	 */
	public function setPackageState(PackageResourceSet $set);
	
	/**
	 * @return array
	 */
	public function getPackageNames();
}