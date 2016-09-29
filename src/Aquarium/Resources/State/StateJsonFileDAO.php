<?php
namespace Aquarium\Resources\State;


use Aquarium\Resources\Config;
use Aquarium\Resources\Base\State\IStateDAO;

use Objection\Mapper;
use Objection\Mappers;


class StateJsonFileDAO implements IStateDAO
{
	/** @var PackageResourceSet[] */
	private $data = null;
	
	private $filePath = '';
	private $isModified = false;
	
	
	private function preLoad()
	{
		if (!is_null($this->data)) return;
		
		if (!$this->filePath) 
		{
			$this->filePath = Config::instance()->directories()->StateFile;
		}
		
		$data = file_get_contents($this->filePath);
		
		if (!$data) $data = '[]';
		
		/** @var PackageResourceSet[] $objects */
		$objects = Mappers::simple()->getObjects($data, PackageResourceSet::class);
		$this->data = [];
		
		foreach ($objects as $object)
		{
			$this->data[$object->PackageName] = $object;
		}
	}
	
	private function save()
	{
		if (!$this->isModified) return; 
		
		$jsonData = Mappers::simple()->getJson($this->data, JSON_PRETTY_PRINT);
		
		if (file_put_contents($this->filePath, $jsonData, LOCK_EX) === false)
		{
			throw new \Exception('Failed to write to the state file: ' . $this->filePath);
		}
	}
	
	
	public function __destruct()
	{
		$this->save();
	}
	
	
	/**
	 * @param string $name
	 * @return PackageResourceSet
	 */
	public function getPackageState($name)
	{
		$this->preLoad();
		
		return (isset($this->data[$name]) ? $this->data[$name] : false); 
	}
	
	/**
	 * @param PackageResourceSet $set
	 */
	public function setPackageState(PackageResourceSet $set)
	{
		$this->preLoad();
		
		$this->data[$set->PackageName] = $set;
	}
	
	/**
	 * @return array
	 */
	public function getPackageNames()
	{
		$this->preLoad();
		
		return array_keys($this->data);
	}
}