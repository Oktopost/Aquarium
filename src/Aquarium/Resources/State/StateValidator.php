<?php
namespace Aquarium\Resources\State;


use Aquarium\Resources\Package;
use Aquarium\Resources\Base\State\IStateValidator;


/**
 * @autoload
 */
class StateValidator implements IStateValidator
{
	private $notValidatedPackageNames = [];
	private $truckPackages = false;
	
	/**
	 * @autoload
	 * @var \Aquarium\Resources\Base\State\IStateDAO
	 */
	private $dao;
	
	
	/**
	 * @param Package $source
	 * @param Package $target
	 * @return PackageResourceSet
	 */
	private function createPackageResourceSet(Package $source, Package $target)
	{
		
	}
	
	
	/**
	 * @param Package $source
	 * @param Package $target
	 * @return bool
	 */
	public function isModified(Package $source, Package $target)
	{
		$current = $this->createPackageResourceSet($source, $target);
		$last = $this->dao->getPackageState($source->getName());
		
		unset($this->notValidatedPackageNames[$source->getName()]);
		
		if (is_null($last))
			return true;
		
		return $current->isModified($last);
	}
	
	/**
	 * @param Package $source
	 * @param Package $target
	 */
	public function saveNewState(Package $source, Package $target)
	{
		$set = $this->createPackageResourceSet($source, $target);
		$this->dao->setPackageState($set);
	}
	
	/**
	 * If not called, getNotValidatedPackageNames will always return an emty array.
	 */
	public function truckValidatedPackages()
	{
		$this->truckPackages = true;
		$this->notValidatedPackageNames = array_flip($this->dao->getPackageNames());
	}
	
	/**
	 * Get array of all package names for which isModified was never called.
	 * @return array
	 */
	public function getNotValidatedPackageNames()
	{
		return array_keys($this->notValidatedPackageNames);
	}
	
	/**
	 * @return array
	 */
	public function getPackageNames()
	{
		return $this->dao->getPackageNames();
	}
}