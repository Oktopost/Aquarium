<?php
namespace Aquarium\Resources\Base\State;


use Aquarium\Resources\Package;


/**
 * @skeleton
 */
interface IStateValidator
{
	/**
	 * @param Package $source
	 * @param Package $target
	 * @return bool
	 */
	public function isModified(Package $source, Package $target);
	
	/**
	 * @param Package $source
	 * @param Package $target
	 */
	public function saveNewState(Package $source, Package $target);
	
	/**
	 * If not called, getNotValidatedPackageNames will always return an emty array.
	 */
	public function truckValidatedPackages();
	
	/**
	 * Get array of all package names for which isModified was never called.
	 * @return array
	 */
	public function getNotValidatedPackageNames();
	
	/**
	 * @return array
	 */
	public function getPackageNames();
}