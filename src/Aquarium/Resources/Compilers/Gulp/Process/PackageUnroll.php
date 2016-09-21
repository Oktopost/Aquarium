<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceCollection;


class PackageUnroll
{
	/** @var Package */
	private $package;
	
	/** @var ResourceCollection */
	private $collection;
	
	/** @var Package\IPackageDefinitionManager */
	private $definitionManager;
	
	
	/**
	 * @param string $propertyName
	 * @param Package $package
	 */
	private function getElements($propertyName, Package $package)
	{
		/** @var ResourceCollection $styles */
		$styles = $package->$propertyName;
		$this->collection->add($styles);
		
		$this->getRequiredElements($propertyName, $package);
		$this->getInscribedElements($propertyName, $package);
	}
	
	/**
	 * @param string $propertyName
	 * @param Package $package
	 */
	private function getRequiredElements($propertyName, Package $package)
	{
		foreach ($package->Requires as $required)
		{
			$requiredPackage = $this->definitionManager->get($required);
			$this->getElements($propertyName, $requiredPackage);
		}
	}
	
	/**
	 * @param string $propertyName
	 * @param Package $package
	 */
	private function getInscribedElements($propertyName, Package $package)
	{
		foreach ($package->Inscribed as $inscribed)
		{
			$requiredPackage = $this->definitionManager->get($inscribed);
			$this->getElements($propertyName, $requiredPackage);
		}
	}
	
	/**
	 * @param Package\IPackageDefinitionManager $definitionManager
	 */
	public function __construct(Package\IPackageDefinitionManager $definitionManager)
	{
		$this->definitionManager = $definitionManager;
	}
	
	
	/**
	 * @param Package $package
	 */
	public function setOriginPackage(Package $package)
	{
		$this->package = $package;
	}
	
	/**
	 * @return ResourceCollection
	 */
	public function getStyles()
	{
		$this->collection = new ResourceCollection();
		$this->getInscribedElements('Styles', $this->package);
		$this->collection->add($this->package->Styles);
		return $this->collection;
	}
	
	/**
	 * @return ResourceCollection
	 */
	public function getScripts()
	{
		$this->collection = new ResourceCollection();
		$this->getInscribedElements('Scripts', $this->package);
		$this->collection->add($this->package->Scripts);
		return $this->collection;
	}
}