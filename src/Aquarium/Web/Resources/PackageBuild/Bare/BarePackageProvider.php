<?php
namespace Aquarium\Web\Resources\PackageBuild\Bare;


use \Aquarium\Web\Resources\IPackageProvider;
use \Aquarium\Web\Resources\IProvider;
use \Aquarium\Web\Resources\PackageBuild\PhpSetup;


class BarePackageProvider implements IPackageProvider {
	
	/** @var BareBuilder */
	private $builder;
	
	/** @var PhpSetup */
	private $setupClass;
	
	
	public function __construct() {
		$this->setupClass = new PhpSetup();
	}
	
	
	/**
	 * @param IProvider $provider
	 * @return static
	 */
	public function setProvider(IProvider $provider) {
		$this->builder = new BareBuilder();
		$this->builder->setPackageProvider($this);
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function addPackage($name) {
		$this->setupClass->setup($name, $this->builder);
	}
}