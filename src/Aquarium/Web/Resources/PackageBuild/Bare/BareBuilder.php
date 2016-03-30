<?php
namespace Aquarium\Web\Resources\PackageBuild\Bare;


use \Aquarium\Web\Resources\IConsumer;
use \Aquarium\Web\Resources\IPackageProvider;
use \Aquarium\Web\Resources\PackageBuild\IBuilder;


class BareBuilder implements IBuilder {
	
	/** @var IConsumer */
	private $consumer;
	
	/** @var IPackageProvider */
	private $provider;
	
	
	/**
	 * @param IConsumer $c
	 * @return static
	 */
	public function setConsumer(IConsumer $c) {
		$this->consumer = $c;
		return $this;
	}
	
	/**
	 * @param IPackageProvider $pp
	 * @return static
	 */
	public function setPackageProvider(IPackageProvider $pp) {
		$this->provider = $pp;
		return $this;
	}
	
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function addPackage($name) {
		$this->provider->addPackage($name);
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function addScript($path) {
		$this->consumer->addScript($path);
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function addStyle($path) {
		$this->consumer->addStyle($path);
		return $this;
	}
}