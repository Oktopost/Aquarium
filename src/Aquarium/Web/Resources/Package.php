<?php
namespace Aquarium\Web\Resources;


class Package {
	
	private $name;
	private $path;
	
	
	/**
	 * @param $name
	 */
	public function __construct($name) {
		$this->name = $name;
		$this->path = explode(Utils::PACKAGE_PATH_SEPARATOR, $name);
	}

	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param int|bool $at
	 * @return array
	 */
	public function getPath($at = false) {
		if ($at === false) {
			return $this->path;
		}
		
		return (
			isset($this->path[$at]) ? 
				$this->path[$at] : 
				null);
	}
}