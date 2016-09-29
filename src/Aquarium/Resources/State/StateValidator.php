<?php
namespace Aquarium\Resources\State;


use Aquarium\Resources\Config;
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
	 * @param Package $p
	 * @param string $dir
	 * @return ResourceSet
	 */
	private function createResourceSet(Package $p, $dir)
	{
		$set = new ResourceSet();
		
		$length = strlen($dir);
		$all = array_merge($p->Scripts->get(), $p->Views->get(), $p->Styles->get());
		sort($all);
		
		foreach ($all as $item)
		{
			$item = substr($item, $length + 1);
			
			$file = new ResourceFile();
			$file->Path = $item;
			
			$set->Files[$item] = $file; 
		}
		
		return $set;
	}
	
	/**
	 * @param Package $source
	 * @param Package $target
	 * @return PackageResourceSet
	 */
	private function createPackageResourceSet(Package $source, Package $target)
	{
		$setSource = $this->createResourceSet($source, Config::instance()->directories()->ResourcesSourceDirs[0]);
		$setTarget = $this->createResourceSet($target, Config::instance()->directories()->CompiledResourcesDir);
		
		$packageSet = new PackageResourceSet();
		
		$packageSet->PackageName = $source->Name;
		$packageSet->Source = $setSource;
		$packageSet->Target = $setTarget;
		$packageSet->setRootPath();
		
		return $packageSet;
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