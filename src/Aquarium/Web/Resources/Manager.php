<?php
namespace Aquarium\Web\Resources;


class Manager implements IProvider {
	
	/** @var IConsumer */
	private $consumer;
	
	/** @var IPackageProvider */
	private $packageProvider;
	
	
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
		$this->packageProvider = $pp;
		$this->packageProvider->setProvider($this);
		return $this;
	}
	
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function package($name) {
		$this->packageProvider->addPackage($name);
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function script($path) {
		$this->consumer->addScript($path);
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return static
	 */
	public function style($path) {
		$this->consumer->addStyle($path);
		return $this;
	}
}